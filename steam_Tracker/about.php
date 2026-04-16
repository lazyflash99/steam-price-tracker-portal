<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
</head>

<body>
    <style>
        .para{
            font-size: larger;
        }
        .top {
            padding: 1%;
            border: solid;
            background-image: url(https://imgs.search.brave.com/1XV8WVuxoJlHJgnohyM0G9z3vWAtlNlsv7MV_eAfNtk/rs:fit:500:0:1:0/g:ce/aHR0cHM6Ly91cGxv/YWQud2lraW1lZGlh/Lm9yZy93aWtpcGVk/aWEvY29tbW9ucy84/LzgwL0lJVEcuanBn);
            background-size: cover;
            background-position: center -600px;
            font-size: x-large;
        }

        .both {
            display: flex;
            height: 100vh;
        }

        .left {
            align-items: center;
            width: 20%;
            background-color: lightblue;

        }

        .right {
            padding: 1.5%;
            width: 80%;
            background-color: lightpink;
        }

        .image {
            display: block;
            margin: auto;
        }
    </style>
    <div class="top"  align="center">
        <b> Mehta Family School of Data Science and Artificial Intelligence
            <br>
            IIT Guwahati
        </b>
        <br>
        Welcome to our Information Portal
    </div>
    <div class="both">
        <div class="left" align="center">
            <br>
            <button onclick="location.href='registration.php'" class="btn">Registration</button>
            <br>
            <br>
            <button onclick="location.href='login.php'" class="btn">Login</button>
        </div>
        <div class="right">
            <p>
                 <h1>About School of DS&AI</h1>
                <br>
                <br>
                <p class="para">
                The Data Science and AI knowledge base is witnessing theoretical advancements and technological breakthroughs. Newer applications are emerging at a rapid pace. The future AI-skilled Workforce will need a knowledge, skills, and competencies which blur the boundaries between disciplines.
                </p>
                <br>
                <p class="para">Established at IIT Guwahati and setup with support from the Mehta Family Foundation in 2021, the School aims to be a leading place working on creating knowledge by analyzing different kinds of datasets and developing methods to engineer intelligence. We adopt an interdisciplinary approach to research with contributions from the fields including but not limited to mathematics, electrical engineering, computer science, psychology, humanities, chemistry, and biology. With our undergraduate and postgraduate programs, we aim to create engineers and researchers ready to take up any data science & AI challenge. In this quest, we welcome joining hands with organizations from across the globe.
                </p><br>
                <p class="para">Starting in 2021, the School introduced BTech and PhD programs in DS&AI. By 2023, it extended its involvement into the MTech in Data Science program, offered jointly with Dept Mathematics and Dept. EEE of the institute. In October 2023, a pioneering move was made with the launch of the BSc (Hons.) in DS&AI online program, designed to reach a global audience. The architectural plans for the School's new building were unveiled in July 2023, with construction currently underway.
                </p>
            </p>
        </div>
    </div>
</body>

</html>