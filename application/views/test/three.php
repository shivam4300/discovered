<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Three.js Canvas as Fabric.js Image</title>
<!-- Include Three.js from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r132/three.min.js"></script>
<!-- Include Fabric.js from CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
</head>
<body>
<!-- Placeholder for Fabric.js canvas -->
<canvas id="fabric-canvas" width="800" height="600"></canvas>
<h2 id="result" style="color:green;"></h2>

<!-- This is the Hellow world example -->
<script>
    // Three.js scene setup
    var scene = new THREE.Scene();
    var camera = new THREE.PerspectiveCamera(24, 4 / 3, 0.1, 1000);
    var renderer = new THREE.WebGLRenderer({ alpha: true });
    renderer.setSize(800, 600);
    renderer.setClearColor(0x000000, 0);

    const pointLight = new THREE.PointLight(0xffffff, 1.5);
    
    pointLight.position.set(0, 100, 90);
    scene.add(pointLight);

    // Add 3D text to the scene
    var loader = new THREE.FontLoader();
    
    // Load a font and create 3D text
    loader.load('https://local.app-discovered.tv/repo/font.json', function (font) {
        
        var textGeometry = new THREE.TextGeometry('Ajaydeep Parmar', {
            font: font,
            size: 20,
            height: 5,
            curveSegments: 12,
            bevelEnabled: true,
            bevelThickness: 10,
            bevelSize: 3,
            bevelOffset: 0,
            bevelSegments: 5
        });
        textGeometry.computeBoundingBox();
        
        // Get the bounding box size
        var textWidth = textGeometry.boundingBox.max.x - textGeometry.boundingBox.min.x;
        var textHeight = textGeometry.boundingBox.max.y - textGeometry.boundingBox.min.y;
        var textDepth = textGeometry.boundingBox.max.z - textGeometry.boundingBox.min.z;

        var textMaterial = new THREE.MeshPhongMaterial({ color:'white' });
        var mesh = new THREE.Mesh(textGeometry, textMaterial);

        // Set the position of the mesh to center the text
        // Subtract half the width, height, and depth from the mesh position
        mesh.position.x = -0.5 * textWidth;
        mesh.position.y = -0.5 * textHeight;
        mesh.position.z = -0.5 * textDepth;

        scene.add(mesh);

         // Camera position
        camera.position.z = 500;
        
        // Render the scene
        renderer.render(scene, camera);
        
        // Convert the rendered canvas to data URL
        var dataURL = renderer.domElement.toDataURL();
        
        // Fabric.js setup
        var fabricCanvas = new fabric.Canvas('fabric-canvas');
        
        // Create a Fabric.js image object using the data URL
        fabric.Image.fromURL(dataURL, function(img) {
        // Add the image to the Fabric.js canvas
        fabricCanvas.add(img);
        });

        let res = document.getElementById('result');

        function GFG_Fun() {
            let img = document.createElement('img');
            img.src = dataURL;
            res .appendChild(img);
        }
        GFG_Fun()


    });
    
   
</script>


<!--script>
    
    const renderer = new THREE.WebGLRenderer();
    renderer.setSize( window.innerWidth, window.innerHeight );
    document.body.appendChild( renderer.domElement );

    const camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 1, 500 );
    camera.position.set( 0, 0, 100 );
    camera.lookAt( 0, 0, 0 );

    const scene = new THREE.Scene();


    const material = new THREE.LineBasicMaterial( { color: 0x0000ff } );

    const points = [];
    points.push( new THREE.Vector3( - 10, 0, 0 ) );
    points.push( new THREE.Vector3( 0, 10, 0 ) );
    points.push( new THREE.Vector3( 10, 0, 0 ) );

    const geometry = new THREE.BufferGeometry().setFromPoints( points );
    
    const line = new THREE.Line( geometry, material );

    scene.add( line );
    renderer.render( scene, camera );
   
</script-->

</body>
</html>