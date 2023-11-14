<!DOCTYPE html>
<html>
<head>
  <title>Траст-фактор в CS:GO</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url("53.jpg");
      background-repeat: no-repeat;
      background-size: cover;
      margin: 0;
      padding: 20px;
    }

    h1 {
      color: #fff;
      text-align: center;
    }

    form {
      text-align: center;
      margin-bottom: 20px;
    }

    label {
      font-weight: bold;
      color: #fff;
    }

    input[type="text"] {
      padding: 5px;
      margin: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
    }

    button[type="submit"] {
      padding: 8px 16px;
      background-color: #007bff;
      color: white;
      border: none;
      border-radius: 3px;
      cursor: pointer;
    }

    .trust-good {
      background-color: #78c15e;
      color: white;
      padding: 10px;
      text-align: center;
      font-size: 18px;
      border-radius: 5px;
    }

    .trust-bad {
      background-color: #dc3545;
      color: white;
    }

    .trust-black {
      background-color: #000;
      color: white;
    }
  </style>
</head>
<body>
  <h1>Траст-фактор в CS:GO</h1>
  <form method="post" action="">
    <label for="steamId">Steam ID:</label>
    <input type="text" name="steamId" id="steamId" required>
    <button type="submit">Получить траст-фактор</button>
  </form>

  <?php
  // Connect to the database
  $db_host = 'localhost';
  $db_user = 'csgo_user';
  $db_password = ''; 
  $db_name = 'csgo_trust_factor';

  $conn = new mysqli($db_host, $db_user, $db_password, $db_name);

  // Check the connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  // Process form after submission
  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $steamId = $_POST["steamId"];

      if (!empty($steamId)) {
          $url = "https://api.steampowered.com/ISteamUser/GetPlayerBans/v1/?key=your_steam_api_key&steamids=" . $steamId;
          $response = file_get_contents($url);
          $data = json_decode($response, true);

          if (!empty($data["players"])) {
              $trustFactor = $data["players"][0]["EconomyBan"];
              $trustClass = '';

              if ($trustFactor == "none") {
                  $trustClass = 'trust-good';
                  echo "<p class=\"$trustClass\">На вашем аккаунте отсутствуют нарушения и ограничения.</p>";
              } else {
                  $trustClass = 'trust-bad';
                  echo "<p class=\"$trustClass\">На вашем аккаунте имеются нарушения или ограничения.</p>";
              }

              // Store the Steam ID and trust factor in the database
              $sql = "INSERT INTO trust_factors (steam_id, trust_factor) VALUES ('$steamId', '$trustFactor')";
              $conn->query($sql);
          } else {
              echo "<p class=\"trust-black\">Не удалось получить данные для Steam ID $steamId.</p>";
          }
      } else {
          echo "<p class=\"trust-black\">Введите Steam ID.</p>";
      }
  }

  // Close the database connection
  $conn->close();
  ?>
</body>
</html>
