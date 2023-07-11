<?php
require_once('./header.php');
require 'vendor/autoload.php';
header("Access-Control-Allow-Origin: *");
session_start();

// var_dump($_SESSION["user"]);
// var_dump($_SESSION["token"]);

?>
<div style="display: flex; flex-direction: column; align-items: center; justify-content: space-between;">
    <h1 class="title is-1">Routes</h1>
    <div id="routes"></div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script>
    var array_points = []

    function getRoutes() {
        fetch('http://localhost:4002/get_routes.php', {
                method: 'GET',
                mode: 'cors'
            })
            .then(response => response.json().then(
                data => {
                    console.log(data);
                    for (let i = 0; i < data.routes.length; i++) {
                        document.getElementById('routes').innerHTML += `
                        <div class="card" style="margin: 20px 0">
                        <header class="card-header">
                            <p class="card-header-title" title="${data.routes[i].points}">
                            ${data.routes[i].name}
                            </p>
                        </header>
                        <footer class="card-footer">
                            <a href="#" class="card-footer-item">View</a>
                            <a href="#" class="card-footer-item">Download</a>
                        </footer>
                        </div>
                        `
                    }
                }
            ))
            
    }

    
    getRoutes();
</script>
<?php
require_once('./footer.php');
?>