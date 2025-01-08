@extends('layouts.guest')

@section('title', 'Contact Us - UKM Space')

@section('content')
<section class="hero">
    <div class="container text-center py-5">
        <h1 class="display-4 fw-bold">Contact Us</h1>
        <p class="text-muted">Your Ultimate College Community Event Partner</p>
    </div>
</section>

<section class="contact-section py-5">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-6">
                <div class="card shadow-sm p-4">
                    <h2 class="mb-4">Send Us a Message</h2>
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your full name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="5" placeholder="Enter your message" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </form>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-6">
                <div>
                    <h3>Contact Info</h3>
                    <p class="text-muted">We would love to hear from you! Reach out to us at:</p>
                    <p>Email: <a href="mailto:info@ukmspace.com">info@ukmspace.com</a></p>
                    <p>Phone: +62 812 3456 7890</p>
                </div>

                <div class="mt-4">
                    <h3>Marketing Collaborations</h3>
                    <p>Email: <a href="mailto:partnerships@ukmspace.com">partnerships@ukmspace.com</a></p>
                </div>

                <div class="mt-4">
                    <h3>Careers</h3>
                    <p>Email: <a href="mailto:hr@ukmspace.com">hr@ukmspace.com</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
