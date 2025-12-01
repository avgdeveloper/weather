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
