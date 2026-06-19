<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .dashboard-container {
            max-width: 900px;
            margin: 0 auto;
        }

        .card {
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            border: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        .user-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            color: white;
        }

        .header h1 {
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Dashboard</h1>
            <a class="btn btn-light" href="{{ route('logout') }}">Logout</a>
        </div>

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Welcome, {{ $user->name }}!</h5>
            </div>
            <div class="card-body">
                <div class="user-info">
                    <h6 class="fw-bold mb-3">Your Information</h6>
                    <p><strong>Name:</strong> {{ $user->name }}</p>
                </div>


                <h4>All Recent Tickets</h4>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Title</th>
                            <th scope="col">Status</th>
                            <th scope="col">Description</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr data-ticket-id="{{ $ticket->ticket_id }}">
                                <th scope="row">{{ $ticket->id }}</th>
                                <td>{{ $ticket->title }}</td>
                                <td>
                                    @switch($ticket->status)
                                        @case('open')
                                            <span class="badge bg-dark">Open</span>
                                        @break

                                        @case('in_progress')
                                            <span class="badge bg-warning text-dark">In Progress</span>
                                        @break

                                        @case('closed')
                                            <span class="badge bg-success">Resolved</span>
                                        @break

                                        @default
                                            <span class="badge bg-secondary">Unknown</span>
                                    @endswitch
                                </td>
                                <td>{{ $ticket->description }}</td>
                                <td>{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#ticketModal" data-title="{{ $ticket->title }}"
                                        data-status="{{ $ticket->status }}"
                                        data-description="{{ $ticket->description }}"
                                        data-created-at="{{ $ticket->created_at->format('Y-m-d H:i') }}"
                                        data-id="{{ $ticket->ticket_id }}" data-email="{{ $ticket->email }}"
                                        data-name="{{ $ticket->name }}">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-center">
                    {{ $tickets->links() }}
                </div>

                <h6 class="fw-bold mb-3 mt-4">Quick Actions</h6>
                <div class="d-grid gap-2 d-md-flex">
                    <a class="btn btn-danger btn-sm" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="ticketModal" tabindex="-1" aria-labelledby="ticketModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ticketModalLabel">Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            //on modal show, populate the content
            $('#ticketModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var title = button.data('title'); // Extract info from data-* attributes
                var status = button.data('status');
                var description = button.data('description');
                var createdAt = button.data('created-at');
                var ticketId = button.data('id');
                var email = button.data('email');
                var name = button.data('name');

                var modal = $(this);
                modal.find('.modal-title').text(title);
                modal.find('.modal-body').html(
                    `<div class="mb-3 pb-3 border-bottom">
                        <p class="mb-1"><strong>User Name:</strong> ${name}</p>
                        <p class="mb-0"><strong>Email:</strong> ${email}</p>
                    </div>
                    <p><strong>Status:</strong> ${status}</p>
                    <p><strong>Description:</strong> ${description}</p>
                    <p><strong>Created At:</strong> ${createdAt}</p>

                    <form classs="">
                        <input type="hidden" name="ticket_id" value="${ticketId}">
                        <div class="form-group">
                            <label>Status Update</label>
                            <select class="form-control" name="status">
                                <option value="open">Open</option>
                                <option value="in_progress">In Progress</option>
                                <option value="closed">Resolved</option>
                            </select>
                        </div>

                        <button type="button" class="btn btn-primary mt-3 updateTicket" onclick="updateTicket(this, event)">Update Status</button>
                    </form>
                    `
                );
            });
        });

        //jquery delegate updateTicket
        /*
         * TupdateTicket
         */
        window.updateTicket = function(e, event) {
            event.preventDefault();
            var element = $(e);
            var status = element.closest('form').find('select[name="status"]').val();
            var ticketId = element.closest('form').find('input[name="ticket_id"]').val();
            //use ajax
            $.ajax({
                type: "POST",
                url: "{{ route('update.ticket.status') }}",
                data: {
                    ticket_id: ticketId,
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                beforeSend: function() {
                    element.closest('form').find('button[type="submit"]').html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...`
                    ).prop('disabled', true);
                },
                success: function(response) {
                    //release the button
                    element.closest('form').find('button[type="submit"]').html('Update Status').prop(
                        'disabled', false);
                    if (response.success) {
                        alert(response.message);
                        //update the status in the table
                        var row = $('tr[data-ticket-id="' + ticketId + '"]');
                        row.find('td:nth-child(3)').html(
                            `<span class="badge ${status === 'open' ? 'bg-dark' : status === 'in_progress' ? 'bg-warning text-dark' : 'bg-success'}">${status === 'open' ? 'Open' : status === 'in_progress' ? 'In Progress' : 'Resolved'}</span>`
                        );
                    } else {
                        alert(response.message);
                    }
                },
                error: function(xhr, status, error) {
                    element.closest('form').find('button[type="submit"]').html('Update Status').prop(
                        'disabled', false);
                    alert('An error occurred: ' + error);
                }
            });
        }
    </script>
</body>

</html>
