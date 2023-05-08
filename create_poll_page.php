<?php
require_once 'functions.php';
session_start();

if (!auth_is_logged_in()) redirect('auth_login_page.php');
if (!auth_is_admin()) redirect('index.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div id="errors"></div>
    <form action="query_create_poll.php" method="post" id="form" onsubmit="validateForm()">
        <label for="question">Question: </label>
        <input type="text" name="question" id="question"> <br>
        <label for="optionCount">Number of Options</label>
        <input type="number" id="optionCount" min=2 value=2 onchange="generateTextInput()"> <br>
        <div id="optionInputList">
            <input type="text" name="options[]" >
            <input type="text" name="options[]" >
        </div>
        <label for="deadline">Deadline: </label>
        <input type="datetime-local" name="deadline" id="deadline"> <br>
        <input type="checkbox" name="isUniqueAnswer" id="isUniqueAnswer">
        <label for="isUniqueAnswer">single answer</label> <br>
        <input type="submit" value="Create">
    </form>

    <script>
        deadline = document.getElementById('deadline');
        deadline.setAttribute('min', new Date().toJSON().substring(0,16));

        function validateForm() {
            event.preventDefault();
            var errorDiv = document.getElementById('errors');
            errorDiv.innerHTML = '';
            var question = document.getElementById('question');
            // console.log(question)
            // console.log(question.value.length);
            if (question.value.length < 1) {
                p = document.createElement('p');
                p.innerText = "Please fill the valid question";
                // console.log(p);
                errorDiv.appendChild(p);
                return false;
            }
            optionBox = document.getElementById('optionInputList');
            optionNodes = optionBox.children;
            console.log(optionNodes)
            for(var i = 0, len = optionNodes.length; i < len; i++) {
                option = optionNodes[i];
                if (option.value.length < 1) {
                    p = document.createElement('p');
                    p.innerText = "Please fill the valid options";
                    errorDiv.appendChild(p);
                    return false;
                }
            }

            deadline = document.getElementById('deadline').value;
            if (deadline.length < 16) return false;
            document.getElementById('form').submit();
            return true;
        }
        function generateTextInput() {
            optionBox = document.getElementById('optionInputList');
            optionBox.innerHTML = '';
            optionCount = document.getElementById('optionCount').value;
            for (var i = 0; i < optionCount; i++) {
                inputElement = document.createElement('input');
                inputElement.setAttribute('type','text');
                inputElement.setAttribute('name','options[]');
                optionBox.appendChild(inputElement);
            }
        }
    </script>
</body>
</html>

