<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  <script src="./script.js"></script>
  
  <style>
    #result-container{display: none;}
  </style>

  <title>Forecast</title>
</head>


<body>
  <div class="container mt-5 pt-5">
    <div class="row">
      <div class="col-11 m-auto">

          <p class="h3 text-center mb-4">
            <span class="title text-primary">Jacket</span> or no <span class="title text-primary">Jacket</span>
          </p>

          <form>
            <div class="row">
              <div class="col-6">
                <input id="city-input" type="text" class="form-control form-control-sm" placeholder="Enter city name here (E-g New York)">
              </div>
              <div class="col-3">
                <button type="button" onclick="getData('getFromAPI')" class="btn btn-primary btn-sm btn-block">Get from API</button>
              </div>
              <div class="col-3">
                <button type="button" onclick="getData('getFromDB')" class="btn btn-warning btn-sm btn-block">Get from DB</button>
              </div>
            </div>
          </form>

          <hr>

          <!--  Showing when a button was clicked -->
          <div id="result-container">
            <div id="data-container" class="border p-3 mb-3"></div>
            <table class="table table-borderless table-sm">
              <thead class="thead-borderless">
                <tr class="text-primary">
                  <th scope="col">Datetime</th>
                  <th scope="col">Min temp</th>
                  <th scope="col">Max temp</th>
                  <th scope="col">Wind speed</th>
                </tr>
              </thead>
              <tbody id="data-tbody"></tbody>
            </table>
          </div>
          <!--  End of the result container -->

      </div>
    </div>
  </div>  
</body>

</html>
