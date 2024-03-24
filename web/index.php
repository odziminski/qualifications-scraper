<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/mvp.css">
    <title>justjoin.it tech scraper</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .js { color: #F0DB4F; }
        .html { color: #E34C26; }
        .php { color: #777BB4; }
        .java { color: #007396; }
        .python { color: #3776AB; }
        .ruby { color: #CC342D; }
        .net { color: #512BD4; }
        .c { color: #5C6BC0; }
        .testing { color: #607D8B; }
        .devops { color: #03A9F4; }
        .go { color: #00ADD8; }
        .scala { color: #DC322F; }
        .mobile { color: #689F38; }
        .security { color: #9C27B0; }
        .other { color: #000000; }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
    </style>
</head>
<body>
<header>
    <h3>find the most demanded tech in every IT niche.</h3>
    <p>technologies to choose:</p>
    <ul>
        <li><a href="tech.php/js" class="js">javascript</a></li>
        <li><a href="tech.php/html" class="html">html</a></li>
        <li><a href="tech.php/php" class="php">php</a></li>
        <li><a href="tech.php/java" class="java">java</a></li>
        <li><a href="tech.php/python" class="python">python</a></li>
        <li><a href="tech.php/ruby" class="ruby">ruby</a></li>
        <li><a href="tech.php/net" class="net">dot net</a></li>
        <li><a href="tech.php/c" class="c">c/c++/c#</a></li>
        <li><a href="tech.php/testing" class="testing">testing</a></li>
        <li><a href="tech.php/devops" class="devops">devops</a></li>
        <li><a href="tech.php/go" class="go">go</a></li>
        <li><a href="tech.php/scala" class="scala">scala</a></li>
        <li><a href="tech.php/mobile" class="mobile">mobile</a></li>
        <li><a href="tech.php/security" class="security">security</a></li>
        <li><a href="tech.php/other" class="other">other</a></li>
    </ul>
    <div id="content"></div> <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function(){
            $("ul a").click(function(e){
                var tech = $(this).data('technology');
                $.ajax({
                    url: '/qualifications/web/category.php',
                    type: 'GET',
                    data: { technology: tech },
                    success: function(data) {
                        console.log(data);
                        showContent(data);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        });

        function showContent(data) {
            var container = document.createElement("div");
            container.classList.add("content-container");

            var title = document.createElement("h2");
            title.textContent = data.technology;
            container.appendChild(title);

            var list = document.createElement("ul");
            for (var i = 0; i < data.qualifications.length; i++) {
                var item = document.createElement("li");
                item.textContent = data.qualifications[i];
                list.appendChild(item);
            }
            container.appendChild(list);

            $("#content").html(container);
        }
    </script>
</header>
</body>
</html>