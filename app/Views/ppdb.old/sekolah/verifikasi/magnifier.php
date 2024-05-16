<!DOCTYPE html>

<html lang="en">

<body>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/magnifier.js/magnifier.css">

    <div>
        <div class="magnifier-preview" id="preview" style="width: 400px; height: 266px">
            <div class="magnifier-thumb-wrapper" style="z-index: 10001; width: 100px; height: 100px; cursor: pointer;">
                <img id="thumb" src="http://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Starry_Night_Over_the_Rhone.jpg/200px-Starry_Night_Over_the_Rhone.jpg" width="100%" style="border: 2px solid white"> 
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?php echo base_url();?>assets/magnifier.js/Event.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>assets/magnifier.js/Magnifier.js"></script>

    <script type="text/javascript">

        var evt = new Event(),
            m = new Magnifier(evt);

        m.attach({
            thumb: '#thumb',
            large: 'http://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Starry_Night_Over_the_Rhone.jpg/400px-Starry_Night_Over_the_Rhone.jpg',
            largeWrapper: 'preview'
        });

    </script>
</body>

</html>

