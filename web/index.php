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
   <?php
$techTopics = [
    'javascript' => 'js',
    'html' => 'html',
    'php' => 'php',
    'java' => 'java',
    'python' => 'python',
    'ruby' => 'ruby',
    'dot net' => 'net',
    'c/c++/c#' => 'c',
    'testing' => 'testing',
    'devops' => 'devops',
    'go' => 'go',
    'scala' => 'scala',
    'mobile' => 'mobile',
    'security' => 'security',
    'other' => 'other'
    ];

    echo '<ul>';
    foreach ($techTopics as $name => $argument) {
    echo '<li><a href="tech.php/' . $argument . '" class="' . $argument . '">' . $name . '</a></li>';
    }
    echo '</ul>';
   ?>
    <div id="content"></div>

</header>
</body>
</html>