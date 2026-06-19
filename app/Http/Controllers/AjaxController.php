<?php

namespace App\Http\Controllers;

use App\Models\TicketManagement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AjaxController extends Controller
{

    /**
     * showLoginForm
     *
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }


    /**
     * showRegisterForm
     *
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }


    /**
     * Greet the user - Simple AJAX POST example
     */
    public function greet(Request $request)
    {
        $name = $request->input('name', 'Guest');

        return response()->json([
            'success' => true,
            'message' => "Hello, {$name}! Welcome to Laravel AJAX.",
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'data' => [
                'name' => $name,
                'greeting' => "This is a response from the server for {$name}"
            ]
        ]);
    }

    /**
     * Get all users - Simple AJAX GET example
     */
    public function getUsers()
    {
        $users = User::all(['id', 'name', 'email'])->toArray();

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'count' => count($users),
            'data' => $users,
            'timestamp' => now()->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Store data - AJAX POST with JSON example
     */
    public function storeData(Request $request)
    {
        // Get JSON data
        $data = $request->all();

        // Validate if needed
        $validated = $request->validate([
            'title' => 'nullable|string',
            'content' => 'nullable|string'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data stored successfully',
            'received_data' => $data,
            'validated_data' => $validated,
            'timestamp' => now()->format('Y-m-d H:i:s'),
            'server_info' => [
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'user_agent' => $request->header('User-Agent')
            ]
        ]);
    }

    /**
     * Login user
     */
    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6'
            ]);

            $user = User::where('email', $request->input('email'))->first();

            //validate user credentials
            if (!$user || !Hash::check($request->input('password'), $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid email or password'
                ], 401);
            }

            //login the user
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => route('dashboard')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during login',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Register user
     */
    public function register(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6'
            ]);

            //check for validation failure
            if ($validated->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed:' . implode(', ', $validated->errors()->all())
                ], 422);
            }

            $user = User::create([
                'name' => $request->input('username'),
                'email' => $request->input('email'),
                'password' => Hash::make($request->input('password'))
            ]);

            //login user after registration
            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'redirect' => route('dashboard')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during registration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * submitTicket
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    function submitTicket(Request $request)
    {
        try {
            //Validate all required fields
            $validated = Validator::make($request->all(), [
                'username' => 'required|string|max:255',
                'email' => 'required|email',
                'tickettitle' => 'required|string|max:255',
                'ticketdescription' => 'required|string'
            ]);

            //check if validation fails
            if ($validated->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed:' . implode(', ', $validated->errors()->all()),
                ], 422);
            }

            //Generate a unique ticket ID.
            $ticketId = 'TICKET-' . strtoupper(uniqid());

            //save to model
            $ticket = TicketManagement::create([
                'ticket_id' => $ticketId,
                'name' => $request->input("username"),
                'email' => $request->input("email"),
                'title' => $request->input("tickettitle"),
                'description' => $request->input("ticketdescription")
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ticket submitted successfully',
                'data' => $ticket
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while submitting the ticket',
                'error' => $th->getMessage() . ' in ' . $th->getFile() . ' on line ' . $th->getLine(),
            ], 500);
        }
    }


    /**
     * Logout
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login.form');
    }


    /**
     * updateTicketStatus
     *
     */
    public function updateTicketStatus(Request $request)
    {
        try {
            $validated = Validator::make($request->all(), [
                'ticket_id' => 'required|exists:ticket_management,ticket_id',
                'status' => 'required|in:open,in_progress,closed'
            ]);

            if ($validated->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed:' . implode(', ', $validated->errors()->all()),
                ], 422);
            }

            $ticket = TicketManagement::where('ticket_id', $request->input('ticket_id'))->first();
            $ticket->status = $request->input('status');
            $ticket->save();

            return response()->json([
                'success' => true,
                'message' => 'Ticket status updated successfully',
                'data' => $ticket
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the ticket status',
                'error' => $th->getMessage() . ' in ' . $th->getFile() . ' on line ' . $th->getLine(),
            ], 500);
        }
    }
}
