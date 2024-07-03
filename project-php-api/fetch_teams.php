<?php
if (isset($_GET['search'])) {
    $searchQuery = $_GET['search'];
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api-football-v1.p.rapidapi.com/v3/teams?search=" . urlencode($searchQuery),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => [
            "x-rapidapi-host: api-football-v1.p.rapidapi.com",
            "x-rapidapi-key: ddf44d7b09msh8633722eb2c9310p194e18jsn5d2d817577c6"
        ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo json_encode([]);
    } else {
        $data = json_decode($response, true);
        $teams = array_map(function($team) {
            return [
                'id' => $team['team']['id'],
                'name' => $team['team']['name']
            ];
        }, $data['response']);
        echo json_encode($teams);
    }
}
?>
