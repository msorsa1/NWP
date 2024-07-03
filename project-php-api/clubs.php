<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Football Teams Search</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,700" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        .search-button {
            display: inline-block;
            outline: none;
            cursor: pointer;
            font-size: 16px;
            line-height: 20px;
            font-weight: 600;
            border-radius: 8px;
            padding: 14px 24px;
            border: none;
            transition: box-shadow 0.2s ease 0s, -ms-transform 0.1s ease 0s, -webkit-transform 0.1s ease 0s, transform 0.1s ease 0s;
            background: linear-gradient(to right, rgb(230, 30, 77) 0%, rgb(227, 28, 95) 50%, rgb(215, 4, 102) 100%);
            color: #fff;
        }
        .search-button:hover {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .player-img {
            width: 50px; /* Adjust as needed */
            height: 50px; /* Adjust as needed */
            background-size: cover;
            background-position: center;
            border-radius: 50%;
        }
    </style>
    <script>
        let debounceTimeout;

        //punis team dropdown
        function fetchTeams() {
            const searchQuery = document.getElementById('teamInput').value;
            if (!searchQuery) return;  // Do not send request if the input is empty

            clearTimeout(debounceTimeout);

            debounceTimeout = setTimeout(() => {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'fetch_teams.php?search=' + searchQuery, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        const teams = JSON.parse(this.responseText);
                        const dropdown = document.getElementById('teamDropdown');
                        dropdown.innerHTML = '';

                        teams.forEach(team => {
                            const option = document.createElement('option');
                            option.value = team.id;
                            option.textContent = team.name;
                            dropdown.appendChild(option);
                        });
                    }
                };
                xhr.send();
            }, 300); //300 ms 
        }

        function retrievePlayers() {
            const teamId = document.getElementById('teamDropdown').value;
            if (teamId) {
                const xhr = new XMLHttpRequest();
                xhr.open('GET', 'retrieve_players.php?team_id=' + teamId, true);
                xhr.onload = function() {
                    if (this.status == 200) {
                        const playersSection = document.getElementById('players');
                        const tableBody = document.querySelector('table tbody');
                        tableBody.innerHTML = '';  // Clear previous results

                        const response = JSON.parse(this.responseText);
                        const players = response.response[0].players;

                        players.forEach(player => {
                            const row = document.createElement('tr');
                            row.className = 'alert';
                            row.role = 'alert';

                            row.innerHTML = `
                                <td><img src="${player.photo}" alt="Player Image" class="player-img"></td>
                                <td>${player.name}</td>
                                <td>${player.age}</td>
                                <td>${player.number}</td>
                                <td>${player.position}</td>
                            `;
                            tableBody.appendChild(row);
                        });
                    }
                };
                xhr.send();
            } else {
                alert('Please select a team.');
            }
        }
    </script>
</head>
<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 text-center mb-5">
                    <h2 class="heading-section">Football Teams Search</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <input type="text" id="teamInput" placeholder="Enter team name" onkeyup="fetchTeams()">
                    <select id="teamDropdown">
                        <option value="">Select a team</option>
                    </select>
                    <button class="search-button" type="button" onclick="retrievePlayers()">Retrieve players</button>
                    <div class="table-wrap">
                        <table class="table table-responsive-xl">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Player Name</th>
                                    <th>Age</th>
                                    <th>Number</th>
                                    <th>Position</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Player data will be dynamically inserted here -->
                            </tbody>
                        </table>
                    </div>
                    <div id="players"></div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/jquery.min.js"></script>
    <script src="js/popper.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>
