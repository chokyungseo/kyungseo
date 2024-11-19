<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>날씨보관함</title>
    <style>
        body {
            margin: 0;
            font-family: Sandoll 서울, Sandoll 서울 ,Sandoll 서울;
            background-color: #ffffff;
        }

        .title {
            text-align: center;
            font-size: 80px;
            margin-top: 20px;
            color: #1ea635;
            text-decoration: bold;
        }

        .subtitle {
            text-align: center;
            font-size: 40px;
            margin-top: 10px;
            color: #000000;
        }

        .form-container {
            position: fixed;
            top: 170px;
            left: 50%;
            transform: translateX(-50%);
            text-align: center;
        }

        .form-container input[type="text"],
        .form-container textarea {
            font-family: Sandoll 서울;
           
            font-size: 25px;
            margin-bottom: 15px;
            padding: 7px;
            width: 500px;
            border: 1px solid #1ea635;
            border-radius: 0px;
        }

        .form-container a {
            display: inline-block;
            top: 15px;
            left: 50%;
            margin-top: 0px;
            font-size: 25px;
            padding: 20px 20px;
            background-color: #1ea635;
            color: white;
            text-decoration: none;
            border-radius: 0px;
        }

        .bigbox {
            position: relative;
            width: 100%;
            height: calc(100vh - 250px);
            margin-top: 250px;
            overflow: hidden;
        }

        .smallbox {
            position: absolute;
            font-family: Sandoll 서울;
            font-size: 30px;
            color: #333;
            padding: 20px;
            background-color: #ffeaa7;
            border: 1px solid #000000;
            border-radius: 0px;
            box-shadow: 2px 2px 8px rgba(0, 0, 0, 0.1);
            .smallbox:active {
            cursor: grabbing;
        }
    </style>
</head>
<body>
    <div class="title">날씨보관함</div>
    <div class="subtitle">오늘의 날씨와 기분을 일기장에 기록해주세요! 기록들은 날씨보관함에 기록됩니다.</div>

    <div class="form-container">
        <form action="writeok.php" name="simplewrite" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="이름"><br>
            <textarea name="content" placeholder="날짜.날씨.기분"></textarea><br>
            <a href="#" onClick="writeText()">보관하기</a>
        </form>
    </div>

    <div class="bigbox" id="bigbox">
        <?php
        $dbconn = new mysqli("localhost", "cks040224", "koya!0912", "cks040224");
        $query = "SELECT * FROM MESSAGE ORDER BY idx DESC";
        $result = $dbconn->query($query);
        $result_num = $result->num_rows;

        if ($result_num > 0) {
            while ($data = $result->fetch_assoc()) {
                echo "<div class='smallbox'>".$data["idx"]." : ".$data["name"]." : ".$data["msg"]."</div>";
            }
        }
        ?>
    </div>

    <script>
        function writeText() {
            document.simplewrite.submit();
        }

        // 포스트잇 위치와 색상 랜덤 설정
        window.onload = function () {
            const boxes = document.querySelectorAll('.smallbox');
            const bigBox = document.getElementById('bigbox');
            const bigBoxWidth = bigBox.offsetWidth;
            const bigBoxHeight = bigBox.offsetHeight;

            boxes.forEach(box => {
                // 무작위 위치 설정
                const randomX = Math.random() * (bigBoxWidth - box.offsetWidth);
                const randomY = Math.random() * (bigBoxHeight - box.offsetHeight);
                box.style.left = `${randomX}px`;
                box.style.top = `${randomY}px`;

                // 무작위 색상 설정
                const colors = ['#4bd162', '#ff8787', '#3dbbff', '#ffd230', '#ff9130', '#cd90fc'];
                const randomColor = colors[Math.floor(Math.random() * colors.length)];
                box.style.backgroundColor = randomColor;

                // 드래그 앤 드롭 이벤트 추가
                box.addEventListener('mousedown', startDrag);
            });
        };

        let selectedBox = null;
        let offsetX = 0;
        let offsetY = 0;

        function startDrag(event) {
            selectedBox = event.target;
            offsetX = event.clientX - selectedBox.offsetLeft;
            offsetY = event.clientY - selectedBox.offsetTop;

            document.addEventListener('mousemove', moveBox);
            document.addEventListener('mouseup', stopDrag);
        }

        function moveBox(event) {
            if (selectedBox) {
                selectedBox.style.left = `${event.clientX - offsetX}px`;
                selectedBox.style.top = `${event.clientY - offsetY}px`;
            }
        }

        function stopDrag() {
            document.removeEventListener('mousemove', moveBox);
            document.removeEventListener('mouseup', stopDrag);
            selectedBox = null;
        }
    </script>
</body>
</html>