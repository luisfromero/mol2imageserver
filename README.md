# MolServer

Molserver es un servidor de imágenes que forma parte de la suite de aplicaciones [molfinder](http://molfinder.ual.es). En concreto, es el componente responsable de:

* La generación del conjunto de proyecciones aleatorias de una molécula, y que se utilizan para la consulta al motor de Inteligencia Artificial Molnet2D.
* La visualización de una pareja de moléculas utilizando [ChemDoodle](#licencias) y [Open Babel](#licencias)

## Generacion de imágenes

Al componente de generación de las imágenes se accede mediante la página [index.php](/index.php). El resultado es una lista en texto plano de las url relativas al servidor, así como la generación, en una carpeta, de la imágenes proyectadas. A la página se le pasan los siguientes parámetros GET:

* mol=molname: Es el nombre de la molécula, utilizando el nombre del archivo de la base de datus [Drugbank](https://go.drugbank.com/) sin extensión, y respetando las mayúsculas. Este parámetro es obligatorio.
* num=numero: Número de imágenes que se van a generar. En caso de que no se especifique, se utilizará el número indicado en el código (8)
* debug: parámetro que se utiliza para mostrar información adicional. Además, junto la lista plana de imágenes generadas se muestran las urls de las imágenes, así como una previsualización de las mismas.
* seed=numero: Semilla utilizada para la generación aleatoria. Si no se especifica, se utilizará una marca temporal.
* hq: En este caso se generan imágenes en calidad superior (256x256 píxeles)

## Visualización de las moléculas

La página [visor.php](/visor.php) muestra dos ventanas, con una molécula cada una. Las dos moléculas se pasan como parámetros GET, con el nombre de la molécula sin extensión. Para cada una de ellas, el proceso consiste en crear el canvas, cargar una molecula "por defecto", y leer la molécula consultada usando ChemDoodle:

```js
var wh=500;
let canvas1 = new ChemDoodle.TransformCanvas3D('canvas1', wh, wh);
let canvas2 = new ChemDoodle.TransformCanvas3D('canvas2', wh, wh);
cargaInicial(canvas1,nombre1);
cargaInicial(canvas2,nombre2);
ChemDoodle.io.file.content('http://molfinder.ual.es/molserver/mol/'+molecula1,callback1 );
ChemDoodle.io.file.content('http://molfinder.ual.es/molserver/mol/'+molecula2,callback2 );	
```

Las funciones cargaInicial, callback1 y callback2 se implementan es el código javascript anexo [visor.js](/js/visor.js). En este código, la parte más interesante se implementa en la función _miondrag_ (my onDrag) que permite girar a la vez las dos moléculas. Para ello es necesario conocer el inverso de las matrices de rotación de las dos moléculas en el momento en el que se activa esta funcionalidad (oldmat1 y oldmat2). A partir de ese momento, si se rota la molécula A, se calcula cómo debe moverse la molécula B mediante un par de operaciones matriciales y se aplica.

## Licencias
<a name="chemdoodle"></a>
This project uses Chemdoodle, a chemical graphics and cheminformatics software.<br/>
Copyright © 2012 iChemLabs, LLC. All rights reserved.<br/>
Licensed under the GPL 3.0.
Visit https://www.chemdoodle.com for more information.
 
<a name="obabel"></a>
This project uses Open Babel, a chemical toolbox designed to speak the many languages of chemical data.<br/>
Copyright © 2002-2018 Open Babel Contributors. All rights reserved.<br/>
Licensed under the GPL 3.0.
Visit http://openbabel.org for more information.
