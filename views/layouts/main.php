<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
//use app\models\Usuario;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<div class="wrap">

<?php
	$usuario = Yii::$app->user->identity;
	$menuItems = [];
	$menuVentanilla = [];
	$menuFinanciero = [];
	$menuAlumno = [];
	array_push($menuAlumno, ['label' => 'Información personal', 'url' => ['/informacionpersonal/index']]);
	array_push($menuAlumno, ['label' => 'Culminación', 'url' => ['/notasalumnoasignatura/index']]);
	array_push($menuAlumno, ['label' => 'Histórico notas', 'url' => ['/notasalumnoasignatura/historico']]);
	#array_push($menuItems, ['label' => 'Distributivo', 'url' => ['/cursoofertado/index']]);
	if ($usuario) {
		if ($usuario->idperfil == 'diracad' || $usuario->idperfil == 'sa' || $usuario->idperfil == 'atics') {
			#array_push($menuItems, ['label' => 'Alumno', 'url' => ['/informacionpersonal/index']]);
			array_push($menuItems, ['label' => 'Asignatura', 'url' => ['/asignatura/index']]);
			array_push($menuItems, ['label' => 'Alumno', 'items' => $menuAlumno]);
			#array_push($menuItems, ['label' => 'Calificaciones', 'url' => ['/notasalumnoasignatura/index']]);
			#array_push($menuItems, ['label' => 'Histórico notas', 'url' => ['/notasalumnoasignatura/historico']]);
			array_push($menuItems, ['label' => 'Distributivo', 'url' => ['/cursoofertado/index']]);
			array_push($menuItems, ['label' => 'Equivalencias', 'url' => ['/equivalencia/index']]);
			array_push($menuItems, ['label' => 'Extensión matrícula', 'url' => ['/extensionmatricula/index']]);
			array_push($menuItems, ['label' => 'Extensión Docente', 'url' => ['/extensiondocente/index']]);
			array_push($menuItems, ['label' => 'Ingreso', 'url' => ['/ingreso/index']]);
			array_push($menuItems, ['label' => 'Malla carrera', 'url' => ['/mallacarrera/index']]);
			array_push($menuItems, ['label' => 'Malla requisito', 'url' => ['/mallarequisito/index']]);
			array_push($menuItems, ['label' => 'Malla estudiante', 'url' => ['/mallaestudiante/index']]);
			array_push($menuItems, ['label' => 'Notas sic a siad', 'url' => ['/notassic/index']]);

			#array_push($menuVentanilla, ['label' => 'Financiero', 'url' => ['/abonofactura/index']]);
			array_push($menuVentanilla, ['label' => 'Gestión matrícula', 'url' => ['/factura/index']]);
			array_push($menuVentanilla, ['label' => 'Gestión docente', 'url' => ['/cursoofertado/docente']]);
			array_push($menuVentanilla, ['label' => 'Gestión docente 1S-2017', 'url' => ['/docenteperasig/index']]);
			array_push($menuVentanilla, ['label' => 'Listar matrícula', 'url' => ['/detallematricula/vercupos']]);
			array_push($menuVentanilla, ['label' => 'Listar notas', 'url' => ['/detallematricula/vernotas']]);
			array_push($menuVentanilla, ['label' => 'Listar aprobados', 'url' => ['/notasalumnoasignatura/veraprobados']]);
			array_push($menuVentanilla, ['label' => 'Listar por puntaje', 'url' => ['/notasalumnoasignatura/vermejores']]);
			array_push($menuVentanilla, ['label' => 'Listar egresados', 'url' => ['/detallematricula/reporte_egresados']]);
			
			array_push($menuFinanciero, ['label' => 'Crear Pago', 'url' => ['/abonofactura/abonar']]);
			array_push($menuFinanciero, ['label' => 'Consultar Pagos', 'url' => ['/abonofactura/index']]);

			
		}
		elseif ($usuario->idperfil == 'coord') {
			#array_push($menuItems, ['label' => 'Alumno', 'url' => ['/informacionpersonal/index']]);
			array_push($menuItems, ['label' => 'Alumno', 'items' => $menuAlumno]);
			#array_push($menuItems, ['label' => 'Calificaciones', 'url' => ['/notasalumnoasignatura/index']]);
			#array_push($menuItems, ['label' => 'Histórico notas', 'url' => ['/notasalumnoasignatura/historico']]);
			array_push($menuItems, ['label' => 'Distributivo', 'url' => ['/cursoofertado/index']]);
			array_push($menuItems, ['label' => 'Malla carrera', 'url' => ['/mallacarrera/index']]);
			array_push($menuItems, ['label' => 'Malla requisito', 'url' => ['/mallarequisito/index']]);

			array_push($menuVentanilla, ['label' => 'Gestión matrícula', 'url' => ['/factura/index']]);
			array_push($menuVentanilla, ['label' => 'Gestión docente', 'url' => ['/cursoofertado/docente']]);
			array_push($menuVentanilla, ['label' => 'Gestión docente 1S-2017', 'url' => ['/docenteperasig/index']]);
			array_push($menuVentanilla, ['label' => 'Listar matrícula', 'url' => ['/detallematricula/vercupos']]);
			array_push($menuVentanilla, ['label' => 'Listar notas', 'url' => ['/detallematricula/vernotas']]);
			array_push($menuVentanilla, ['label' => 'Listar aprobados', 'url' => ['/notasalumnoasignatura/veraprobados']]);
			array_push($menuVentanilla, ['label' => 'Listar por puntaje', 'url' => ['/notasalumnoasignatura/vermejores']]);
			array_push($menuVentanilla, ['label' => 'Listar egresados', 'url' => ['/detallematricula/reporte_egresados']]);
		}
		elseif ($usuario->idperfil == 'secacad' || $usuario->idperfil == 'deca' || $usuario->idperfil == 'be' ) {
			#array_push($menuItems, ['label' => 'Alumno', 'url' => ['/informacionpersonal/index']]);
			array_push($menuItems, ['label' => 'Alumno', 'items' => $menuAlumno]);
			#array_push($menuItems, ['label' => 'Calificaciones', 'url' => ['/notasalumnoasignatura/index']]);
			#array_push($menuItems, ['label' => 'Histórico notas', 'url' => ['/notasalumnoasignatura/historico']]);
			array_push($menuVentanilla, ['label' => 'Gestión matrícula', 'url' => ['/factura/index']]);
			array_push($menuVentanilla, ['label' => 'Listar matrícula', 'url' => ['/detallematricula/vercupos']]);
			array_push($menuVentanilla, ['label' => 'Listar notas', 'url' => ['/detallematricula/vernotas']]);
			array_push($menuVentanilla, ['label' => 'Listar aprobados', 'url' => ['/notasalumnoasignatura/veraprobados']]);
			array_push($menuVentanilla, ['label' => 'Listar por puntaje', 'url' => ['/notasalumnoasignatura/vermejores']]);
			array_push($menuVentanilla, ['label' => 'Listar egresados', 'url' => ['/detallematricula/reporte_egresados']]);
		}
		elseif ($usuario->idperfil == 'centros') {
			#array_push($menuItems, ['label' => 'Alumno', 'url' => ['/informacionpersonal/index']]);
			array_push($menuItems, ['label' => 'Alumno', 'items' => $menuAlumno]);
			#array_push($menuItems, ['label' => 'Calificaciones', 'url' => ['/notasalumnoasignatura/index']]);
			#array_push($menuItems, ['label' => 'Histórico notas', 'url' => ['/notasalumnoasignatura/historico']]);
			array_push($menuItems, ['label' => 'Malla estudiante', 'url' => ['/mallaestudiante/index']]);

			array_push($menuVentanilla, ['label' => 'Gestión matrícula', 'url' => ['/factura/index']]);
			array_push($menuVentanilla, ['label' => 'Gestión docente', 'url' => ['/cursoofertado/docente']]);
			array_push($menuVentanilla, ['label' => 'Gestión docente 1S-2017', 'url' => ['/docenteperasig/index']]);
			array_push($menuVentanilla, ['label' => 'Listar matrícula', 'url' => ['/detallematricula/vercupos']]);
			array_push($menuVentanilla, ['label' => 'Listar notas', 'url' => ['/detallematricula/vernotas']]);
			array_push($menuVentanilla, ['label' => 'Listar aprobados', 'url' => ['/notasalumnoasignatura/veraprobados']]);
			array_push($menuVentanilla, ['label' => 'Listar por puntaje', 'url' => ['/notasalumnoasignatura/vermejores']]);
			array_push($menuVentanilla, ['label' => 'Listar egresados', 'url' => ['/detallematricula/reporte_egresados']]);
		}
		elseif ($usuario->idperfil == 'fin') {
			array_push($menuFinanciero, ['label' => 'Crear Pago', 'url' => ['/abonofactura/abonar']]);
			array_push($menuFinanciero, ['label' => 'Consultar Pagos', 'url' => ['/abonofactura/index']]);
			#array_push($menuVentanilla, ['label' => 'Gestión matrícula', 'url' => ['/factura/index']]);
		}
		elseif ($usuario->idperfil == 'snna') {
			array_push($menuItems, ['label' => 'Ingreso', 'url' => ['/ingreso/index']]);
			#array_push($menuVentanilla, ['label' => 'Gestión matrícula', 'url' => ['/factura/index']]);
		}
		elseif ($usuario->idperfil == 'dist') {
			array_push($menuItems, ['label' => 'Distributivo', 'url' => ['/cursoofertado/index']]);
			array_push($menuItems, ['label' => 'Extensión matrícula', 'url' => ['/extensionmatricula/index']]);
			#array_push($menuVentanilla, ['label' => 'Gestión matrícula', 'url' => ['/factura/index']]);
		}
	}
	#else
	#	array_push($menuItems, ['label' => 'Calificaciones', 'url' => ['/notasalumnoasignatura/index']]);
		//echo var_dump(str_replace('"', '', $itemsacademico));exit;
	NavBar::begin([
		'brandLabel' => 'UTELVT',
		'brandUrl' => Yii::$app->homeUrl,
		'options' => [
			'class' => 'navbar-inverse navbar-fixed-top',
		],
	]);

	echo Nav::widget([
		'options' => ['class' => 'navbar-nav navbar-right'],
		'items' => [
			['label' => 'Inicio', 'url' => ['/site/index']],
			
			[
				'label' => 'Académico',
				'items' => $menuItems,
					
			],	
		 
			[
				'label' => 'Ventanilla de Servicios',
				'items' => $menuVentanilla,
			],
		
			[
				'label' => 'Financiero',
				'items' => $menuFinanciero,
			],


			[
				'label' => 'Reportes',
				'items' => [
					['label' => 'Estudiantes matriculados', 'url' => ['/matricula/index']],
					['label' => 'Estudiantes por género', 'url' => ['/matricula/reporte_promedio']],
					['label' => 'Estudiantes por étnia', 'url' => ['/matricula/reporte_etnia']],
					['label' => 'Estudiantes por estado civil', 'url' => ['/matricula/reporte_estado']],
					['label' => 'Estudiantes por discapacidad', 'url' => ['/matricula/reporte_discapacidad']],
					['label' => 'Estudiantes aprobados SNNA', 'url' => ['/notasalumnoasignatura/snna']],
				],
			],

		
			Yii::$app->user->isGuest ? (
				['label' => 'Ingresar', 'url' => ['/site/login']]
			) : (

			'<li>'
			. Html::beginForm(['/site/logout'], 'post')
			. Html::submitButton(
				'Salir (' . Yii::$app->user->identity->LoginUsu . ')',
				['class' => 'btn btn-link']
			)
			. Html::endForm()
			. '</li>'
			)
		],
	]);

	NavBar::end();
?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
	
        <?= $content ?>

    </div>

</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; utelvt <?= date('Y') ?></p>

        <p class="pull-right"><? //Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
