<?php require page('includes/header'); ?>
<div class="content">
    <div class="container">
        <h1>Contact</h1>
        <div class="form">
            <form action="../app/controller/contactController.php"  method="post">
                <input class="form-control" type="text" id="name" name="name" aria-label="Name" placeholder="Your Name *"
                    required />
                    
                <input class="form-control" type="email" id="email" name="email" aria-label="Email" placeholder="Your Email *"
                    required />
                <input class="form-control" type="tel" id="number" name="number" aria-label="Phone Number" placeholder="Your Number *"
                    required />
                <textarea class="message form-control" id="message" name="message" aria-label="Message" placeholder="Your Message *"
                    required></textarea>
                <button type="submit" class="btn  form-control">Send Message</button>
            </form>
        </div>
    </div>
</div>

<style>
    :root {
        --bg: #212529;
        --accent: #72c2db;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        font-size: 10px;
        font-family: Verdana, Arial, sans-serif;
    }

    body {
        background-color: var(--bg);
    }

    .container {
        max-width: 1140px;
        display: flex;
        height: 100%;
        background: #d2ecf3;
        margin: 61px auto;
        border-radius: 23px;
        padding: 6rem 2rem;
        flex-direction: column;
        justify-content: center;
    }

    h1 {
        text-align: center;
        color: #72c2db;
        font-weight: bold;
        font-size: 5rem;
        text-transform: uppercase;
        margin-bottom: 6rem;
    }

    /* Form */

    .form-control {
        width: 100%;
        margin-bottom: 2rem;
        padding: 2.2rem;
        border-radius: 4px;
        border: none;
        font-size: 1.6rem;
        border: 2px solid transparent;
        font-family: Verdana, Arial, sans-serif;
    }

    .form-control::placeholder {
        color: #9da1a5;
        font-weight: bold;
    }

    .message {
        resize: vertical;
        min-height: 15rem;
    }

    .form-control:focus,
    .message:focus {
        outline: none;
        border: 2px solid var(--accent);
    }

    .btn {
        background-color: var(--accent);
        color: #fff;
        width: auto;
        font-size: 1.8rem;
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
        display: block;
        padding-left: 3.5rem;
        padding-right: 3.5rem;
        margin: 0 auto;
        cursor: pointer;
    }

    .btn:hover {
        background-color: #fec810;
    }

    .btn:active {
        box-shadow: 0px 0px 0px 4px rgba(254, 209, 54, 0.6);
    }

    @media (min-width: 700px) {
        form {
            display: grid;
            grid-gap: 2.4rem;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: repeat(4, 1fr);
        }

        .form-control {
            width: auto;
            margin: 0;
        }

        .message {
            grid-column: 2;
            grid-row: 1 / span 3;
        }

        .btn {
            justify-self: center;
            grid-column: span 2;
        }
    }
</style>
<?php require page('includes/footer'); ?>