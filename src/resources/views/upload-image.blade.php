<!DOCTYPE html>
<html>
<head>
    <title>Face upload test app</title>
    <!--link rel="stylesheet" href="http://getbootstrap.com/dist/css/bootstrap.css"-->
    <script src="{{ asset('js/app.js') }}"></script>
    <link href="{{ asset('css/example.css') }}" media="screen" rel="stylesheet" type="text/css" />
    <link href="{{ asset('resources/jquery.selectareas.css') }}" media="screen" rel="stylesheet" type="text/css" />
    <script src="{{ asset('resources/jquery.selectareas.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
			$(document).ready(function () {
				$('img#example').selectAreas({
//					minSize: [10, 10],
					onChanged: debugQtyAreas,
                                        allowEdit: false,
                                        allowMove: false,
//					width: 100%,
					areas: [
					]
				});
			});

			var selectionExists;

			function areaToString (area) {
				return (typeof area.id === "undefined" ? "" : (area.id + ": ")) + area.x + ':' + area.y  + ' ' + area.width + 'x' + area.height + '<br />'
			}

			function output (text) {
				$('#output').html(text);
			}

			// Log the quantity of selections
			function debugQtyAreas (event, id, areas) {
				console.log(areas.length + " areas", arguments);
			};

			// Display areas coordinates in a div
			function displayAreas (areas) {
				var text = "";
				$.each(areas, function (id, area) {
					text += areaToString(area);
				});
				output(text);
			};
		</script>    
</head>
<body>
    <div class="container imgdiv" style="text-align: center;">
<div class="panel panel-primary img" style="display: inline-block;">
  <div class="panel-heading imgdiv">
  <h1>Please upload a photo</h1></div>
  {!! Form::open(array('route' => 'postuplodeimage','files'=>true)) !!}
            <div class="row imgdiv">
                <div class="col-md-6 imgdiv">
                    {!! Form::file('uplode_image_file', array('class' => 'form-control')) !!}
                    <button type="submit" class="btn btn-success imgdiv">Upload</button>
                </div>
            </div>
  {!! Form::close() !!}
    
  <div class="panel-body imgdiv"  style="text-align: left;">
		<!-- count error -->
          @if (count($errors) > 0)
            <div class="alert alert-danger imgdiv">
                <strong>Whoops!</strong> There were some problems with your input.
                <ul>
					<!-- print  error -->
                    @foreach ($errors->all() as $error_val)
                        <li>{{ $error_val }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if ($success_message = Session::get('success'))
        <div class="image-decorator">
            <img src="images/{{ Session::get('image') }}" id="example">
        </div>
        <div class="panel-body imgdiv"  style="text-align: center;">
            
        <div class="alert alert-success alert-block imgdiv">
                <strong>{{ $success_message }}</strong>
        </div>
        <div class="alert alert-success alert-block imgdiv">
                <button type="button" id='getdata'>Get biometric data</button>
        </div>
        <div class="col-md-6 imgdiv" id='biodata'>
        </div>
        </div>
        @endif
  </div>
</div>
</div>
</body>

<script type="text/javascript">
$(document).ready(function() {
    
    $('#getdata').click(function() {

        $('#biodata').html('Loading...');
        $('img#example').selectAreas('reset');        
        
        $.ajax({
            type: "POST",
            data: {
                'image': '{{ Session::get('image') }}',
                '_token': '{{ csrf_token() }}',
            },
            dataType: "json",
            url: "{!! route('biodata') !!}",
            error: function( date ) {
                $('#biodata').html('');
                
                var message = 'Unknow error!';
                        
                try {
                    var msg = jQuery.parseJSON( date.responseText );
                    message = msg.error;
                } catch(e) {

                }

                alert( message );
            },
            success: function( data ) {
                
                if ( data.length === 0 ) $('#biodata').html( 'nothing found' );
                else $('#biodata').html('');
                
                $.each( data, function( index, value ) {
                    var number = index + 1;
                    $('#biodata').append( "<h3> #"+ number +"</h3>");
                    $('#biodata').append( "<h3>"+data[index].race+': '+data[index].race_confidence+"%</h3>");
                    $('#biodata').append( "<h3>"+data[index].sex+': '+data[index].sex_confidence+"%</h3>");
                    
		    var areaOptions = {
			x: data[index].box.x1,
			y: data[index].box.y1,
			width:  data[index].box.x2 - data[index].box.x1,
			height: data[index].box.y2 - data[index].box.y1,
		    };
//		    output("Testing: " + areaToString(areaOptions))
                    
 		    $('img#example').selectAreas('add', areaOptions);
                    
                });
//                console.log( data[0] );




            }
        });
        
//        alert( 'todo' );
    });
});


</script>

</html>

