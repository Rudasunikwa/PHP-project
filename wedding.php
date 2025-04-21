
<?php
$wedding = "wedding.png";
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Step Event Planning Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }

        .form-step {
            display: none;
        }

        .form-step-active {
            display: block;
        }

        button {
            margin-top: 10px;
        }
        .circular-image {

            width: 550px;           /* Set width */
            height: 750px;          /* Set height */
            padding-left: 550px;
            border: 5px solid #333; /* Border properties */
            background-image: url('<?php echo $wedding; ?>');
            
        }
    </style>
</head>
<body>
<div class="circular-image">
<img src="$wedding" alt="Our goal is to make your day special">
</div>
    <div class="form-container">
        
        <form id="eventForm" action="music.php" method="POST">
            <div class="form-step form-step-active">
                <label for="eventCount">How many events do you plan to do?</label>
                <input type="number" id="eventCount" name="eventCount" min="1" required>
                <button type="button" class="next-btn">Next</button>
            </div>

            <div class="form-step">
                <label for="eventSize">How big is your event?</label>
                <select id="eventSize" name="eventSize" required>
                    <option value="small">Small (1-50 people)</option>
                    <option value="medium">Medium (51-200 people)</option>
                    <option value="large">Large (200+ people)</option>
                    <option value="name">I'm not sure!</option>
                </select>
                <button type="button" class="next-btn">Next</button>
            </div>

            <div class="form-step">
                <label for="budget">What is your budget?</label>
                <input type="number" id="budget" name="budget" required>
                <button type="button" class="next-btn">Next</button>
            </div>

            <div class="form-step">
                <h2>Thank you for your input!</h2>
                <a href="post_event.php" >Continue</a>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nextBtns = document.querySelectorAll('.next-btn');
            const formSteps = document.querySelectorAll('.form-step');
            let formStepIndex = 0;

            nextBtns.forEach((button) => {
                button.addEventListener('click', () => {
                    formSteps[formStepIndex].classList.remove('form-step-active');
                    formStepIndex++;
                    formSteps[formStepIndex].classList.add('form-step-active');
                });
            });

            const form = document.getElementById('eventForm');
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                alert('Form submitted successfully!');
                form.reset();
                formStepIndex = 0;
                formSteps.forEach(step => step.classList.remove('form-step-active'));
                formSteps[formStepIndex].classList.add('form-step-active');
            });
        });
    </script>
</body>
</html>
