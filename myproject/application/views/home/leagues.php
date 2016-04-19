<div class="col-xs-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-lg-12">
                   <legend> Football Leagues</legend>
                   <caption>Choose one of leagues from the options below:</caption><br/><br/>
                   <select id="leagueSelect">
                        <option value="0"> -------Select League------- </option>
                        <option value="398">English Premier League</option>
                        <!-- <option value="champship">English Championship</option> -->
                        <option value="425">English League One</option>
                        <!-- <option value="lgetwo">English League Two</option> -->
                        <option value="396">French Ligue 1</option>
                        <option value="397">French Ligue 2</option>
                        <option value="394">German Bundesliga</option>
                        <option value="395">German Bundesliga 2</option>
                        <option value="403">German Bundesliga 3</option>
                        <option value="401">Italian Serie A</option>
                        <!-- <option value="itlge2">Italian Serie B</option> -->
                        <option value="399">Spanish Premiera Division</option>
                        <option value="400">Spanish Liga Adelante</option>
                        <option value="402">Primeira Liga</option>
                        <option value="404">Eredivisie</option>
                        
                    </select>

                    <table class="table" id="ContainerTable" style="display: none;">
                        <tr>
                            <td width="55%" id="tableLeague" style="border: 0 !important;"></td>
                            <td style="border: 0 !important;">
                                <ul class="nav nav-tabs">
                                  <li class="active"><a data-toggle="tab" href="#fixturesBlock">Fixtures</a></li>
                                  <li id="liveTab"><a data-toggle="tab" href="#liveBlock">Results</a></li>
                                </ul>
                                <div class="tab-content">
                                  <div id="fixturesBlock" class="tab-pane fade in active">
                                  </div>
                                  <div id="liveBlock" class="tab-pane fade">
                                      <div class="block_header" style="display: none; margin-top:5px;">
                                      </div>
                                      <div id="liveBlockInner"></div>
                                  </div>
                                </div>
                            </td>
                        </tr>
                    </table>            
            </div>
        </div>
    </div> 
</div>

<script>
$(document).ready(function(){
    //Get base url for requests
    pathArray = location.href.split('/');
    protocol = pathArray[0];
    host = pathArray[2];
    url = protocol + '//' + host + '/';
    $('select').change(function () {
        $('#ContainerTable').show();
        if ($(this).val() != "0") {
          $('.block_header').show();  
           var leagueId = $(this).val();
            //Leagues tables 
            $.ajax({
              type: "GET",
              url: url + "myproject/index.php/home/getTable?leagueId="+leagueId,
              beforeSend: function(){
                $('#tableLeague').html('<img id="imgcode" src="' + url + 'myproject/images/spinner.gif">');
              },
              success: function(response){
                     $('#tableLeague').html('');
                     $('#tableLeague').append (response);
              }
            });
            //Fixtures
            $.ajax({
              type: "GET",
              url: url + "myproject/index.php/home/getFixtures?leagueId="+leagueId,
              beforeSend: function(){
                $('#fixturesBlock').html('<img id="imgcode" src="' + url + 'myproject/images/spinner.gif">');
              },
              success: function(response){
                $("#fixturesBlock").html('');
                $("#fixturesBlock").append(response);
              }
            });
             //Results
            $.ajax({
              type: "GET",
              url: url + "myproject/index.php/home/getResults?leagueId="+leagueId,
              beforeSend: function(){
                $('#liveBlockInner').html('<img id="imgcode" src="' + url + 'myproject/images/spinner.gif">');
              },
              success: function(response){
                $("#liveBlockInner").html('');
                $("#liveBlockInner").append(response);
              }
            });
        }
    });
});

   
function timer(){
 var obj=document.getElementById('timer_inp');
 obj.innerHTML--;
 if(obj.innerHTML==0){
    leagueId = $('#leagueSelect').val();
    if(leagueId != 0){
        //Live results
        $.ajax({
              type: "GET",
              url: url + "myproject/index.php/home/getLiveResults?leagueId="+leagueId,
              beforeSend: function(){
                $('#liveBlockInner').html('<img id="imgcode" src="' + url + 'images/spinner.gif">');
              },
              success: function(response){
                $("#liveBlockInner").html('');
                $("#liveBlockInner").append(response);
              }
            });
    }
    setTimeout(function(){},1000);
    $('#timer_inp').html('25');
    timer();
}
 else{setTimeout(timer,1000);}
}
</script>