@extends('layout.master')
@section('section_body')
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Ticket Management System</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('submit.ticket') }}" method="POST" id="ticketForm">
                        @csrf
                        <!-- Input Section -->
                        <div class="form-group mb-3">
                            <label for="nameInput" class="form-label fw-bold">Enter Your Name:</label>
                            <input type="text" class="form-control" id="nameInput" name="username"
                                placeholder="Enter your name" value="John Doe">
                        </div>
                        <div class="form-group mb-3">
                            <label for="emailInput" class="form-label fw-bold">Enter Your Email:</label>
                            <input type="email" class="form-control" id="emailInput" name="email"
                                placeholder="Enter your email" value="">
                        </div>
                        <div class="form-group mb-3">
                            <label for="ticketTitle" class="form-label fw-bold">Ticket Title</label>
                            <input type="text" class="form-control" id="ticketTitle" placeholder="Enter ticket title"
                                name="tickettitle">
                        </div>
                        <div class="form-group mb-3">
                            <label for="TicketDescription" class="form-label fw-bold">Ticket Description</label>
                            <textarea class="form-control" id="TicketDescription" rows="3" placeholder="Enter ticket description"
                                name="ticketdescription"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            Submit Ticket
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
