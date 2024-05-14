<!DOCTYPE html>

<html lang="en">

<body>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/magnifier.js/magnifier.css">

    <div>
        <img id="image" src="http://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Starry_Night_Over_the_Rhone.jpg/200px-Starry_Night_Over_the_Rhone.jpg" width="400px" style="border: 2px solid white; transform-origin: top left;"> 
    </div>

    <div>
        <a href="#" onclick="rotate_counterclockwise('image', 90); return false;">Rotate Counter Clockwise</a>
        <a href="#" onclick="rotate_clockwise('image', 90); return false;">Rotate Clockwise</a>
    </div>

    <script type="text/javascript">
        var rotation = 0;
        function rotate_clockwise(itemid, degree) {
            var img = document.getElementById(itemid);
            rotation += degree;
            rotation %= 360;
            img.style.transform = 'rotate(' + rotation + 'deg) translateY(-100%)';
            //img.style.transform_origin = 'top left';
       }

        function rotate_counterclockwise(itemid, degree) {
            var img = document.getElementById(itemid);
            rotation -= degree;
            rotation %= 360;
            img.style.transform = 'rotate(' + rotation + 'deg) translateY(-100%)';
            //img.style.transform_origin = 'top left';
         }

    </script>
</body>

</html>

