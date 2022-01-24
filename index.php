<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    
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

<script>

      // Global variable - using for save button for updating/inserting to DB
      var savedData = '';

      // AJAX-API Operation
      function getData(operation){
          var city = $("#city-input").val();
          if (!city) return alert('City-name required!');
          $.ajax({
                url: './server.php',
                dataType: 'json',
                method: 'post',
                data: {
                  city: city,
                  operation: operation,
                  forecast: savedData  // Using for saving forcast operation
                },
                success: function (response) {
                    if (response['code'] == 200) {
                        //Getting from DB operation
                        if (response['op'] == 'db') {
                            DBGenerateHtml(response['data']);
                        }
                        // Getting from API operation
                        else if (response['op'] == 'api') {
                            // store the API result
                            savedData = response['data'];
                            var data = JSON.parse(savedData);
                            if (data['cod'] == 200) 
                                APIGenerateHtml(data);
                            else 
                                alert(data['message']);
                        }
                        else {
                            alert(response.message);
                        }
                    }
                    else {
                      alert(response.message);
                    }
                }
          });
      }


      // Generates DB-result to a HTML document
      // params: data(object) - contains the data to show
      // return nothing

      function DBGenerateHtml(data){

        // Showing the div and change title color
          $('.title').removeClass('text-primary').addClass('text-danger');
          $('#result-container').show();

          // Generating main data div
          $('#data-container').html(`
              <p class="h4">${data['c_city_name']}</p>
              <p>Updated at: ${convertDateTime(data.updated_at)} UTC</p>
          `);

          // Generating table-rows
          $('#data-tbody').html(`
              <tr class="border text-muted">
                  <td> ${ convertDateTime(data.t_date_time) } </td>
                  <td> ${ data['t_temp_min'] }&#8451;</td>
                  <td> ${ data['t_temp_max'] }&#8451;</td>
                  <td> ${ data['w_speed'] } km/h </td>
              </tr>
          `);
      }
      

      // Generates API-result to a HTML document
      // params: data(object) - contains the data to show
      // return nothing

      function APIGenerateHtml(data){
    
        // Showing the div and change title color
        $('.title').removeClass('text-primary').addClass('text-danger');
        $('#result-container').show();

        // Generating main data div
        $('#data-container').html(`
            <p id="city-name" class="h4"> ${ data.city.name} </p>
            <p class="h6">Period</p>
            <p class="text-muted">
              Strats at: ${ convertDateTime(data.list[0].dt_txt) } <br>
              Ends at: ${ convertDateTime(data.list[data.cnt - 1].dt_txt) }
            </p>
            <button type="button" onclick="getData('saveForecast')" class="btn btn-success btn-sm">Save forecast</button>
        `);

        // Generating table-rows
        for (let i = 0; i < data.cnt; i++) {
            $('#data-tbody').append(`
                <tr class="border text-muted">
                    <td> ${ convertDateTime(data.list[i].dt_txt) } </td>
                    <td> ${ data.list[i].main.temp_min } &#8451;</td>
                    <td> ${ data.list[i].main.temp_max } &#8451;</td>
                    <td> ${ data.list[i].wind.speed } km/h </td>
                </tr>
            `); 
        }

      }

      // Converting date-time string to am-pm format
      // params: dataTime(string) - string of the dateTime
      // returns dateTime(string) with an am-pm format and literal string
      
      function convertDateTime(dateTime){
    
          var hour = parseInt(dateTime.substring(11,13));
          
          if (hour <= 12){
              return dateTime.replace(dateTime.substring(11,13), hour) + ' am';
          } 
          else {
              hour = hour - 12;
              return dateTime.replace(dateTime.substring(11,13), hour) + ' pm';
          }
        
      }



</script>

</body>
</html>