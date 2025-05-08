<!-- Rhys Khoo Yong Ke TP076182 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ReviseBio</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="../nonuser/styling/guest-main.css">
</head>

<body>

    <!-- Header -->
    <header>
        <a href="../nonuser/guest-main.php"><img src="../imgs/ReviseBio.png"></a>
        <div class="nav-right">
            <button onclick="location.href='../nonuser/login.php';">Login</button>
            <button onclick="location.href='../nonuser/role-select.php';">Sign up</button>
        </div>
    </header>


    <!-- Welcome Section -->
    <div class="wlc-gst">
        <h1>Master Biology with ReviseBio!</h1>
        <button onclick="location.href='role-select.php';" class="gst-button">Get Started</button>
    </div>

    <!-- Features Section -->
    <div class="gst-main">
        <div>
            <h2 style="padding-bottom: 15px;">ReviseBio offers the following features:</h2>
            <div>
                <!-- Slideshow -->
                <div class="slideshow-container">

                    <div class="slides fade">
                        <img src="../imgs/guestslide1.png" style="width:100%">
                        <p>Study Notes</p>
                    </div>

                    <div class="slides fade">
                        <img src="../imgs/guestslide2.png" style="width:100%">
                        <p>Flash Cards</p>
                    </div>

                    <div class="slides fade">
                        <img src="../imgs/guestslide3.png" style="width:100%">
                        <p>Quiz Challenges</p>
                    </div>

                    <a class="previousslide" onclick="plusSlides(-1)">❮</a>
                    <a class="nextslide" onclick="plusSlides(1)">❯</a>

                </div>
                <br>
                <span class="dot" onclick="currentSlide(1)"></span>
                <span class="dot" onclick="currentSlide(2)"></span>
                <span class="dot" onclick="currentSlide(3)"></span>
            </div>
        </div>
    </div>

    <!-- Sign-Up Section -->
    <div class="gst-signup">
        <div>
            <h3>For Students</h3>
            <p>Study Biology with ease by accessing the structured study notes, taking quizzes and revising through flashcards!</p>
            <button onclick="location.href='role-select.php';" class="gst-button">Sign Up</button>
        </div>
        <div>
            <h3>For Teachers</h3>
            <p>Assess student's performances by creating quizzes and track student's performances on analytics dashboard!</p>
            <button onclick="location.href='role-select.php';" class="gst-button">Sign Up</button>
        </div>
    </div>
    </div>

    <!-- Footer -->
    <?php
    include('nonuser-footer.html')
    ?>

    <!-- Slideshow script -->
    <script>
        let slideIndex = 1;
        showSlides(slideIndex);

        function plusSlides(n) {
            showSlides(slideIndex += n);
        }

        function currentSlide(n) {
            showSlides(slideIndex = n);
        }

        function showSlides(n) {
            let i;
            let slides = document.getElementsByClassName("slides");
            let dots = document.getElementsByClassName("dot");
            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            slides[slideIndex - 1].style.display = "block";
            dots[slideIndex - 1].className += " active";
        }
    </script>

</body>

</html>