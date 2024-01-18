<template>
    <div>
        <section class="container-fluid">
            <div class="row">
                <div class="col-sm-12 px-0 img-responsive">
                    <img src="/img/GAF-home-responsive.png" class="img-fluid w-100" />
                </div>
                <div class="col-lg-12 col-sm-12 px-0 img-desk">
                    <img src="/img/GAF-home.png" class="img-fluid w-100" />
                </div>
                <div
                    class="col-lg-12 col-sm-12 d-flex align-items-start align-items-lg-center justify-content-center marg-resp"
                >
                    <div class="res-center">
                        <p class="text-big font-weight-bold degradado">GAF "Gestor y Administrador Financiero"</p>
                        <div style="display: grid; justify-content: center">
                            <p class="p-text2 text1" style="margin-bottom: 32px">
                                Diseñamos soluciones tecnológicas a medida para impulsar el crecimiento económico de las
                                empresas en Colombia, ofreciendo innovación estratégica y resultados tangibles que
                                potencian su éxito a largo plazo.
                            </p>
                            <div class="d-flex justify-content-center">
                                <b-button v-b-modal.modal-tall variant="black" class="btn-more btn-informacion">¡Conoce Más!</b-button>
                            </div>
                        </div>

                        <b-modal id="modal-tall">
                            <div class="row content-popsec1">
                                <div class="col-lg-12 col-sm-12">
                                    <p class="fancy-text">Nuestras</p>
                                    <span class="text-big-popup d-block text-uppercase"> soluciones </span>
                                </div>
                            </div>
                            <div class="row content-pop2">
                                <div class="col-lg-4 col-sm-12">
                                    <img src="/img/coheteicon.svg" alt="img-asesoria" width="70%" />
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <p class="text-popup pad-sec1">
                                        <span class="font-weight-bold text-spring-green">Diseñadas </span>
                                        para las compañías originadoras<br />
                                        de créditos: La usabilidad hace parte de<br />
                                        nuestros pilares, sabemos que cada cliente<br />
                                        tiene diferentes procesos, productos, por eso<br />
                                        nuestras herramientas son fáciles de usar.
                                    </p>
                                </div>
                            </div>
                            <div class="row content-pop2">
                                <div class="col-lg-8 col-sm-12">
                                    <p class="text-popup pad-sec1">
                                        <span class="font-weight-bold text-spring-green">Innovadoras </span>
                                        Creemos en la era digital y en<br />
                                        sus grandes cambios, por eso GAF invierte en<br />
                                        desarrollar nuevas tecnologías con<br />
                                        funcionalidades y servicios que innoven los<br />
                                        procesos de las industrias.
                                    </p>
                                </div>
                                <div class="col-lg-4 col-sm-12">
                                    <img src="/img/minticon.svg" alt="img-asesoria" width="70%" />
                                </div>
                            </div>
                            <div class="row content-pop2">
                                <div class="col-lg-4 col-sm-12">
                                    <img src="/img/nubeicon.svg" alt="img-asesoria" width="70%" />
                                </div>
                                <div class="col-lg-8 col-sm-12">
                                    <p class="text-popup pad-sec4">
                                        <span class="font-weight-bold text-spring-green">Automáticas </span>
                                        gracias a la inteligencia<br />
                                        artificial entrenamos nuestra herramienta<br />
                                        para que pueda ayudarte en la toma de<br />
                                        decisiones y avanzar trabajos operativos.
                                    </p>
                                </div>
                            </div>
                            <div class="row content-pop2">
                                <div class="col-lg-9 col-sm-12">
                                    <p class="text-popup pad-sec4">
                                        <span class="font-weight-bold text-spring-green">Seguras </span>
                                        en GAF te ofrecemos una experiencia<br />
                                        100% segura gracias a la tecnología blockchain<br />
                                        que nos permite garantizar la seguridad de tus<br />
                                        procesos y la información.
                                    </p>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <img src="/img/monedafinal.svg" alt="img-asesoria" class="img-moneda" />
                                </div>
                            </div>
                        </b-modal>
                    </div>
                </div>
            </div>
        </section>
        <div class="end-section" style=" position: relative; z-index: 1;">
            <div
                class="d-flex justify-content-center align-items-center img-nosotros"
            >
                <h1><strong>¡Nosotros!</strong></h1>
            </div>
            <div style="background-color: #ebebeb">
                <section class="container subscriptions pb-5 pt-0 img-desk" style="margin-top: -45px;">
                    <div class="accordion" role="tablist">
                        <b-card no-body class="card-buttom" style="background-color: #ebebeb">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                                <b-button
                                    class="button-select"
                                    style="border-top: none !important; background-color: #ebebeb"
                                    block
                                    v-b-toggle.accordion-1
                                >
                                    <div class="d-flex align-items-center justify-content-right">
                                        <span class="p-2 text-right" style="min-width: 161px"> Historia GAF </span>
                                        <img src="/img/quienesomos.png" class="img-fluid w-10 cont-img" />
                                    </div>
                                </b-button>
                            </b-card-header>
                            <b-collapse id="accordion-1" accordion="my-accordion" role="tabpanel">
                                <b-card-body class="p-0">
                                    <div class="row">
                                        <div
                                            class="col-3 text-center div-img d-flex align-items-center justify-content-center"
                                            style="max-width: 247px"
                                        >
                                            <div class="text-center">
                                                <img src="/img/quienessomosactive.png" class="img-fluid w-30 mb-4" />
                                                <h4><b>Historia GAF</b></h4>
                                            </div>
                                        </div>
                                        <div class="col-9 p-4">
                                            <div class="row pl-2">
                                                <div class="col-7">
                                                    <p class="text-justify">
                                                        <b>GAF</b> avanza con determinaci&oacute;n en el desarrollo de
                                                        nuestra estrategia corporativa, centrados en forjar un modelo de
                                                        negocio sostenible y rentable. Nos dedicamos a contribuir de
                                                        manera inclusiva a la prosperidad de nuestro pa&iacute;s,
                                                        mejorando la calidad de vida de las personas y fomentando un
                                                        impacto positivo en el medio ambiente. Para alcanzar estos
                                                        objetivos, <b>GAF</b> ha dise&ntilde;ado e implementado diversas
                                                        iniciativas destinadas a fortalecer nuestra propuesta de valor,
                                                        aumentar la productividad de la empresa y agilizar la toma de
                                                        decisiones.
                                                    </p>
                                                </div>
                                                <div class="col-5">
                                                    <img src="/img/gafhistory.png" class="img-fluid w-80" />
                                                </div>
                                                <div class="col-12">
                                                    <p class="text-justify">
                                                        Estamos acelerando nuestra transformaci&oacute;n digital al
                                                        ofrecer soluciones para el mercado de An&aacute;lisis de Mercado
                                                        Inteligente
                                                        <b>(AMI)</b> y la Herramienta Ejecutora de Gestiones Operativas
                                                        y Comerciales <b>(HEGO).</b> Estos proyectos se dividen en
                                                        cuatro fases clave:
                                                    </p>
                                                </div>
                                                <div class="col-12">
                                                    <p class="text-justify">
                                                        <b>Planeacion:</b> Establecemos s&oacute;lidas bases
                                                        estrat&eacute;gicas para cada iniciativa.<br /><b>Ejecucion:</b>
                                                        Implementamos las soluciones de manera efectiva y eficiente.<br /><b
                                                            >Aseguramiento de valor:</b
                                                        >
                                                        Evaluamos y garantizamos el impacto y la eficacia de nuestras
                                                        acciones.<br /><b>(RRHH):</b> Fomentamos un entorno laboral que
                                                        potencia el desarrollo de nuestro equipo, asegurando que
                                                        est&eacute;n alineados con nuestros objetivos y valores.<br />
                                                        <br />Cada una de estas categor&iacute;as est&aacute; respaldada
                                                        por mecanismos de seguimiento y monitoreo, como cronogramas,
                                                        indicadores y mediciones de impacto, para asegurar el
                                                        &eacute;xito continuo de nuestras iniciativas y cumplir con
                                                        nuestro compromiso de crecimiento responsable y sostenible.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-card-body>
                            </b-collapse>
                        </b-card>

                        <b-card no-body class="card-buttom" style="background-color: #ebebeb">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                                <b-button
                                    class="button-select"
                                    style="border-top: none; background-color: #ebebeb"
                                    block
                                    v-b-toggle.accordion-2
                                >
                                    <div class="d-flex align-items-center justify-content-right">
                                        <span class="p-2 text-right" style="min-width: 161px"> ¿Quiénes Somos? </span>
                                        <img src="/img/quienesomos.png" class="img-fluid w-10 cont-img" />
                                    </div>
                                </b-button>
                            </b-card-header>
                            <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                                <b-card-body class="p-0">
                                    <div class="row">
                                        <div
                                            class="col-3 text-center div-img d-flex align-items-center justify-content-center"
                                            style="max-width: 247px"
                                        >
                                            <div class="text-center">
                                                <img src="/img/quienessomosactive.png" class="img-fluid w-30 mb-4" />
                                                <h4><b>¿Quiénes Somos?</b></h4>
                                            </div>
                                        </div>
                                        <div class="col-9 p-4">
                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="text-justify">
                                                        En <b>GAF</b>, Innovamos el Futuro Financiero: Soluciones
                                                        Fintech para el &Eacute;xito Sostenible<br />
                                                        <br />Nos destacamos como l&iacute;deres en el sector Fintech,
                                                        especializados en procesos clave que abarcan la elegibilidad,
                                                        coadministraci&oacute;n, incorporaci&oacute;n y recaudo master.
                                                        Nuestro enfoque se centra en brindar soluciones excepcionales
                                                        dise&ntilde;adas espec&iacute;ficamente para originadores de
                                                        libranza en el sector p&uacute;blico y fondos de
                                                        inversi&oacute;n, asegurando la administraci&oacute;n efectiva
                                                        de sus activos y mitigando los riesgos m&aacute;s
                                                        cr&iacute;ticos identificados por GAF.<br />
                                                        <br />Nuestra dedicaci&oacute;n inquebrantable a la excelencia y
                                                        la innovaci&oacute;n nos ha consolidado como un socio confiable
                                                        y efectivo para las compa&ntilde;&iacute;as que buscan
                                                        transformar y optimizar sus operaciones financieras. En GAF, no
                                                        solo ofrecemos servicios; ofrecemos un camino hacia el
                                                        &eacute;xito sostenible en el din&aacute;mico mundo financiero
                                                        actual.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-card-body>
                            </b-collapse>
                        </b-card>

                        <b-card no-body class="card-buttom" style="background-color: #ebebeb">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                                <b-button
                                    class="button-select"
                                    style="border-top: none; background-color: #ebebeb"
                                    block
                                    v-b-toggle.accordion-3
                                >
                                    <div class="d-flex align-items-center justify-content-right">
                                        <span class="p-2 text-right" style="min-width: 161px"> ¿Donde Estamos? </span>
                                        <img src="/img/dondeestamos.png" class="img-fluid w-10 cont-img" />
                                    </div>
                                </b-button>
                            </b-card-header>
                            <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
                                <b-card-body class="p-0">
                                    <div class="row">
                                        <div
                                            class="col-3 text-center cont-img d-flex align-items-center justify-content-center"
                                            style="max-width: 247px"
                                        >
                                            <div class="text-center">
                                                <img src="/img/dondeestamosactive.png" class="img-fluid w-30 mb-4" />
                                                <h4><b>¿Donde Estamos?</b></h4>
                                            </div>
                                        </div>
                                        <div class="col-9 p-4">
                                            <div class="row">
                                                <div class="col-12 d-flex align-items-center justify-content-center">
                                                    <iframe
                                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.911097495013!2d-76.54234542673285!3d3.3719061517464857!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e30a17a4d89d2f7%3A0x3543b74a3e50b507!2sHolguines%20Trade%20Center%20Centro%20Comercial!5e0!3m2!1ses-419!2sco!4v1702676618990!5m2!1ses-419!2sco"
                                                        width="790"
                                                        height="400"
                                                        style="
                                                            border: 0;
                                                            border-radius: 15px;
                                                            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                                                        "
                                                        allowfullscreen=""
                                                        loading="lazy"
                                                        referrerpolicy="no-referrer-when-downgrade"
                                                    ></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-card-body>
                            </b-collapse>
                        </b-card>

                        <b-card no-body class="card-buttom" style="background-color: #ebebeb">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                                <b-button
                                    class="button-select"
                                    style="border-top: none; background-color: #ebebeb"
                                    block
                                    v-b-toggle.accordion-4
                                >
                                    <div class="d-flex align-items-center justify-content-right">
                                        <span class="p-2 text-right" style="min-width: 161px"> Nuestros Valores </span>
                                        <img src="/img/valores.png" class="img-fluid w-10 cont-img" />
                                    </div>
                                </b-button>
                            </b-card-header>
                            <b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
                                <b-card-body class="p-0">
                                    <div class="row">
                                        <div
                                            class="col-3 text-center cont-img d-flex align-items-center justify-content-center"
                                            style="max-width: 247px"
                                        >
                                            <div class="text-center">
                                                <img src="/img/valoresactive.png" class="img-fluid w-30 mb-4" />
                                                <h4><b>Nuestros Valores</b></h4>
                                            </div>
                                        </div>
                                        <div class="col-9 p-4">
                                            <div class="row">
                                                <div class="col-1 pr-0 d-flex align-items-start justify-content-center">
                                                    <img
                                                        src="/img/compromiso.png"
                                                        class="img-fluid w-25"
                                                        style="width: 50% !important"
                                                    />
                                                </div>
                                                <div
                                                    class="col-11 pb-3 pl-0 d-flex align-items-start justify-content-left"
                                                >
                                                    <p class="text-justify" style="margin-bottom: 0">
                                                        <b>Compromiso con la Excelencia:</b> Buscamos constantemente la
                                                        perfecci&oacute;n en cada detalle, desde la elegibilidad hasta
                                                        el recaudo master, para asegurar la satisfacci&oacute;n total de
                                                        nuestros clientes.
                                                    </p>
                                                </div>
                                                <div class="col-1 pr-0 d-flex align-items-start justify-content-center">
                                                    <img
                                                        src="/img/innovacion.png"
                                                        class="img-fluid w-25"
                                                        style="width: 50% !important"
                                                    />
                                                </div>
                                                <div
                                                    class="col-11 pl-0 pb-3 d-flex align-items-start justify-content-left"
                                                >
                                                    <p class="text-justify" style="margin-bottom: 0">
                                                        <b>Innovaci&oacute;n Sostenible:</b> Nos esforzamos por liderar
                                                        la industria con soluciones creativas y sostenibles que se
                                                        adapten a las cambiantes din&aacute;micas del mercado
                                                        financiero.
                                                    </p>
                                                </div>
                                                <div class="col-1 pr-0 d-flex align-items-start justify-content-center">
                                                    <img
                                                        src="/img/integridad.png"
                                                        class="img-fluid w-25"
                                                        style="width: 50% !important"
                                                    />
                                                </div>
                                                <div
                                                    class="col-11 pl-0 pb-3 d-flex align-items-start justify-content-left"
                                                >
                                                    <p class="text-justify" style="margin-bottom: 0">
                                                        <b>Integridad y Transparencia:</b> Basamos nuestras relaciones
                                                        comerciales en la honestidad y la transparencia, construyendo
                                                        confianza duradera con nuestros clientes y socios.
                                                    </p>
                                                </div>
                                                <div class="col-1 pr-0 d-flex align-items-start justify-content-center">
                                                    <img
                                                        src="/img/enfoque.png"
                                                        class="img-fluid w-25"
                                                        style="width: 50% !important"
                                                    />
                                                </div>
                                                <div
                                                    class="col-11 pl-0 pb-3 d-flex align-items-start justify-content-left"
                                                >
                                                    <p class="text-justify" style="margin-bottom: 0">
                                                        <b>Enfoque en el Cliente:</b> Colocamos las necesidades y
                                                        objetivos de nuestros clientes en el centro de todo lo que
                                                        hacemos, adaptando nuestras soluciones para satisfacer sus
                                                        desaf&iacute;os espec&iacute;ficos.
                                                    </p>
                                                </div>
                                                <div class="col-1 pr-0 d-flex align-items-start justify-content-center">
                                                    <img
                                                        src="/img/personas.png"
                                                        class="img-fluid w-25"
                                                        style="width: 50% !important"
                                                    />
                                                </div>
                                                <div class="col-11 pl-0 d-flex align-items-start justify-content-left">
                                                    <p class="text-justify" style="margin-bottom: 0">
                                                        <b>Personas:</b> Reconocemos que cada persona es esencial para
                                                        nuestro &eacute;xito.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-card-body>
                            </b-collapse>
                        </b-card>

                        <b-card no-body class="card-buttom" style="background-color: #ebebeb">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                                <b-button
                                    class="button-select"
                                    style="border-top: none; background-color: #ebebeb"
                                    block
                                    v-b-toggle.accordion-5
                                >
                                    <div class="d-flex align-items-center justify-content-right">
                                        <span class="p-2 text-right" style="min-width: 161px"> Target </span>
                                        <img src="/img/target.png" class="img-fluid w-10 cont-img" />
                                    </div>
                                </b-button>
                            </b-card-header>
                            <b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
                                <b-card-body class="p-0">
                                    <div class="row">
                                        <div
                                            class="col-3 text-center cont-img d-flex align-items-center justify-content-center"
                                            style="max-width: 247px"
                                        >
                                            <div class="text-center">
                                                <img src="/img/targetactive.png" class="img-fluid w-30 mb-4" />
                                                <h4><b>Target</b></h4>
                                            </div>
                                        </div>
                                        <div class="col-9 p-4">
                                            <div class="row d-flex align-items-center justify-content-center">
                                                <div class="col-12 text-center mb-4">
                                                    <h4><b>Nuestros Clientes:</b></h4>
                                                </div>
                                                <div class="col-6 mb-4 div-boxes">
                                                    <div class="row target-div">
                                                        <div class="col-8">
                                                            <p class="text-justify">
                                                                <b>Principales:</b><br />-Fondos De Capital Privado<br />-Originadores
                                                                de Créditos
                                                            </p>
                                                        </div>
                                                        <div class="col-4 text-center">
                                                            <img src="/img/target1.png" class="img-fluid w-100" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6 mb-4 div-boxes">
                                                    <div class="row target-div">
                                                        <div class="col-8">
                                                            <p class="text-justify">
                                                                <b>Otros:</b><br />-Gestor De Activos Alternativos<br />-Agencias
                                                                De Cobranza
                                                            </p>
                                                        </div>
                                                        <div class="col-4 text-center">
                                                            <img src="/img/target2.png" class="img-fluid w-100" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-4">
                                                    <div class="row">
                                                        <!-- <div
                                                    class="col-4 text-center d-flex align-items-center justify-content-center"
                                                >
                                                    <img src="/img/LogoCK.png" class="img-fluid w-50" />
                                                </div>
                                                <div
                                                    class="col-4 text-center d-flex align-items-center justify-content-center"
                                                >
                                                    <img src="/img/trlogo.png" class="img-fluid w-100" />
                                                </div>
                                                <div
                                                    class="col-4 text-center d-flex align-items-center justify-content-center"
                                                >
                                                    <img src="/img/kblogo.png" class="img-fluid w-100" />
                                                </div> -->

                                                        <div
                                                            class="col-12 d-flex align-items-center justify-content-center"
                                                        >
                                                            <b-carousel style="max-width: 319px" background="white">
                                                                <b-carousel-slide>
                                                                    <template #img>
                                                                        <div style="display: flex">
                                                                            <img
                                                                                class="d-block img-fluid w-100"
                                                                                src="/img/kblogo.png"
                                                                                alt="image slot"
                                                                                style="max-width: 318px; height: 150px"
                                                                            />
                                                                            <img
                                                                                src="/img/LogoCK.png"
                                                                                alt="image slot"
                                                                                style="
                                                                                    max-width: 318px;
                                                                                    max-height: 150px;
                                                                                "
                                                                            />
                                                                            <img
                                                                                class="d-block img-fluid w-100"
                                                                                src="/img/trlogo.png"
                                                                                alt="image slot"
                                                                                style="max-width: 318px; height: 150px"
                                                                            />
                                                                        </div>
                                                                    </template>
                                                                </b-carousel-slide>
                                                                <b-carousel-slide>
                                                                    <template #img>
                                                                        <div style="display: flex">
                                                                            <img
                                                                                src="/img/LogoCK.png"
                                                                                alt="image slot"
                                                                                style="
                                                                                    max-width: 318px;
                                                                                    max-height: 150px;
                                                                                "
                                                                            />
                                                                            <img
                                                                                class="d-block img-fluid w-100"
                                                                                src="/img/trlogo.png"
                                                                                alt="image slot"
                                                                                style="max-width: 318px; height: 150px"
                                                                            />
                                                                            <img
                                                                                class="d-block img-fluid w-100"
                                                                                src="/img/kblogo.png"
                                                                                alt="image slot"
                                                                                style="max-width: 318px; height: 150px"
                                                                            />
                                                                        </div>
                                                                    </template>
                                                                </b-carousel-slide>
                                                                <b-carousel-slide>
                                                                    <template #img>
                                                                        <div style="display: flex">
                                                                            <img
                                                                                class="d-block img-fluid w-100"
                                                                                src="/img/trlogo.png"
                                                                                alt="image slot"
                                                                                style="max-width: 318px; height: 150px"
                                                                            />
                                                                            <img
                                                                                class="d-block img-fluid w-100"
                                                                                src="/img/kblogo.png"
                                                                                alt="image slot"
                                                                                style="max-width: 318px; height: 150px"
                                                                            />
                                                                            <img
                                                                                src="/img/LogoCK.png"
                                                                                alt="image slot"
                                                                                style="
                                                                                    max-width: 318px;
                                                                                    max-height: 150px;
                                                                                "
                                                                            />
                                                                        </div>
                                                                    </template>
                                                                </b-carousel-slide>
                                                            </b-carousel>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <p class="text-justify">
                                                        Nuestra experiencia abarca desde la personalización de
                                                        soluciones para el día a día de los originadores de créditos y
                                                        fondos de inversión hasta atender las necesidades específicas de
                                                        gestores de
                                                        <b>NPLs (Non-Performing Loans).</b>En GAF, estamos comprometidos
                                                        con proporcionar servicios de vanguardia que permitan a nuestros
                                                        clientes avanzar y estabilizarse con confianza en un mundo
                                                        financiero en constante evolución.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-card-body>
                            </b-collapse>
                        </b-card>

                        <b-card no-body style="background-color: #ebebeb">
                            <b-card-header header-tag="header" class="p-1" role="tab">
                                <b-button
                                    class="button-select"
                                    style="
                                        border-bottom: none !important;
                                        border-top: none !important;
                                        background-color: #ebebeb;
                                    "
                                    block
                                    v-b-toggle.accordion-6
                                >
                                    <div class="d-flex align-items-center justify-content-right">
                                        <span class="p-2 text-right" style="min-width: 161px"> Sostenibilidad </span>
                                        <img src="/img/sostenibilidad.png" class="img-fluid w-10 cont-img" />
                                    </div>
                                </b-button>
                            </b-card-header>
                            <b-collapse id="accordion-6" accordion="my-accordion" role="tabpanel">
                                <b-card-body class="p-0">
                                    <div class="row">
                                        <div
                                            class="col-3 text-center cont-img d-flex align-items-center justify-content-center"
                                            style="max-width: 247px"
                                        >
                                            <div class="text-center">
                                                <img src="/img/sostenibilidadactive.png" class="img-fluid w-30 mb-4" />
                                                <h4><b>Sostenibilidad</b></h4>
                                            </div>
                                        </div>
                                        <div class="col-9 p-4">
                                            <div class="row">
                                                <div class="col-6">
                                                    <p class="text-justify">
                                                        <b>GAF</b> avanza con determinaci&oacute;n en el desarrollo de
                                                        nuestra estrategia corporativa, centrados en forjar un modelo de
                                                        negocio sostenible y rentable. Nos dedicamos a contribuir de
                                                        manera inclusiva a la prosperidad de nuestro pa&iacute;s,
                                                        mejorando la calidad de vida de las personas y fomentando un
                                                        impacto positivo en el medio ambiente. Para alcanzar estos
                                                        objetivos, <b>GAF</b> ha dise&ntilde;ado e implementado diversas
                                                        iniciativas destinadas a fortalecer nuestra propuesta de valor,
                                                        aumentar la productividad de la empresa y agilizar la toma de
                                                        decisiones.
                                                    </p>
                                                </div>
                                                <div class="col-6 d-flex align-items-center justify-content-center">
                                                    <img src="/img/imgsostenibilidad.png" class="img-fluid w-50" />
                                                </div>
                                                <div class="col-12">
                                                    <div class="accordion" role="tablist">
                                                        <b-card no-body class="mb-2" style="background-color: #ebebeb">
                                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                                <b-button
                                                                    class="button-sostenibilidad"
                                                                    block
                                                                    v-b-toggle.accordion-7
                                                                    >Seguridad, Gobernanza y Cumplimiento: Pilares de
                                                                    Confianza en GAF</b-button
                                                                >
                                                            </b-card-header>
                                                            <b-collapse
                                                                id="accordion-7"
                                                                accordion="my-accordion2"
                                                                role="tabpanel"
                                                            >
                                                                <b-card-body>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <p class="text-justify">
                                                                                        En GAF, abrazamos la importancia
                                                                                        de la seguridad, gobernanza y
                                                                                        cumplimiento como pilares
                                                                                        fundamentales que sustentan la
                                                                                        confianza en todas nuestras
                                                                                        operaciones. Nuestra dedicación
                                                                                        a estos principios críticos se
                                                                                        traduce en un compromiso
                                                                                        inquebrantable con la
                                                                                        integridad, la transparencia y
                                                                                        la excelencia en cada paso.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/compromiso.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b>Seguridad:</b> Implementamos
                                                                                        rigurosas medidas de seguridad
                                                                                        para salvaguardar los datos y
                                                                                        activos de nuestros clientes.
                                                                                        Desde avanzadas tecnologías
                                                                                        hasta protocolos de gestión de
                                                                                        riesgos, garantizamos un entorno
                                                                                        seguro que protege la
                                                                                        confidencialidad y la integridad
                                                                                        de la información.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/innovacion.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b>Gobernanza:</b> La eficaz
                                                                                        gobernanza es esencial para la
                                                                                        toma de decisiones sólidas y
                                                                                        éticas. En GAF, nuestra
                                                                                        estructura de gobernanza
                                                                                        promueve la responsabilidad, la
                                                                                        transparencia y la alineación
                                                                                        con los objetivos estratégicos,
                                                                                        asegurando una dirección
                                                                                        efectiva y sostenible.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/integridad.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b>Cumplimiento:</b> Cumplir con
                                                                                        regulaciones y estándares es un
                                                                                        compromiso no negociable en GAF.
                                                                                        Mantenemos una adhesión estricta
                                                                                        a las normativas pertinentes,
                                                                                        garantizando la legalidad y
                                                                                        ética en todas nuestras
                                                                                        actividades, y ofreciendo a
                                                                                        nuestros clientes la
                                                                                        tranquilidad de trabajar
                                                                                        comprometidos con la integridad
                                                                                        en los resultados.
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <p class="text-justify">
                                                                                        Desde el ámbito tecnológico
                                                                                        hasta la gestión de datos y más
                                                                                        allá, en GAF, entendemos que la
                                                                                        confianza se construye a través
                                                                                        de la seguridad robusta, una
                                                                                        gobernanza efectiva y el
                                                                                        cumplimiento diligente. Estos
                                                                                        principios no solo son parte de
                                                                                        nuestra estructura, sino que
                                                                                        también son la base sobre la
                                                                                        cual construimos relaciones
                                                                                        sólidas y exitosas con nuestros
                                                                                        clientes y socios.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </b-card-body>
                                                            </b-collapse>
                                                        </b-card>
                                                        <b-card no-body class="mb-2" style="background-color: #ebebeb">
                                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                                <b-button
                                                                    class="button-sostenibilidad"
                                                                    block
                                                                    v-b-toggle.accordion-8
                                                                    >Personas, Innovación Social y Comunidades: Forjando
                                                                    un Futuro Sostenible en GAF</b-button
                                                                >
                                                            </b-card-header>
                                                            <b-collapse
                                                                id="accordion-8"
                                                                accordion="my-accordion2"
                                                                role="tabpanel"
                                                            >
                                                                <b-card-body>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <p class="text-justify">
                                                                                        En GAF, reconocemos que el
                                                                                        verdadero impacto va más allá de
                                                                                        los productos y servicios; se
                                                                                        encuentra en las personas, la
                                                                                        innovación social y el tejido
                                                                                        comunitario. Nos comprometemos a
                                                                                        ser agentes de cambio, guiados
                                                                                        por la creencia de que el
                                                                                        progreso auténtico se construye
                                                                                        alrededor de estos fundamentos.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/compromiso.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b>Personas:</b> En GAF, las
                                                                                        personas son el corazón de
                                                                                        nuestra organización. Valoramos
                                                                                        la diversidad, fomentamos un
                                                                                        ambiente inclusivo y promovemos
                                                                                        el desarrollo personal y
                                                                                        profesional de nuestro equipo.
                                                                                        Creemos que la verdadera fuerza
                                                                                        de GAF reside en la pasión y el
                                                                                        talento de las personas que
                                                                                        forman parte de nuestra familia.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/innovacion.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b>Innovación Social:</b> La
                                                                                        innovación no solo impulsa
                                                                                        nuestras soluciones, sino que
                                                                                        también moldea nuestro
                                                                                        compromiso social. Buscamos
                                                                                        constantemente nuevas formas de
                                                                                        abordar desafíos sociales,
                                                                                        aplicando tecnologías y
                                                                                        estrategias innovadoras para
                                                                                        mejorar la calidad de vida y
                                                                                        fomentar la equidad en las
                                                                                        comunidades que servimos.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/integridad.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b>Comunidades:</b> En
                                                                                        <b>GAF</b>, entendemos que
                                                                                        nuestro éxito está entrelazado
                                                                                        con el bienestar de las
                                                                                        comunidades a las que
                                                                                        pertenecemos. Nos involucramos
                                                                                        activamente en iniciativas
                                                                                        comunitarias, apoyando causas
                                                                                        sociales y contribuyendo al
                                                                                        desarrollo sostenible. Creemos
                                                                                        en el poder de las empresas para
                                                                                        catalizar un cambio positivo y
                                                                                        estamos comprometidos a ser un
                                                                                        agente de transformación en las
                                                                                        comunidades que tocamos.
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <p class="text-justify">
                                                                                        En <b>GAF</b>, nuestra visión no
                                                                                        solo está centrada en el
                                                                                        crecimiento empresarial, sino
                                                                                        también en cómo podemos mejorar
                                                                                        la vida de las personas,
                                                                                        fomentar la innovación social y
                                                                                        fortalecer las comunidades en
                                                                                        las que vivimos y trabajamos.
                                                                                        Cada paso que damos refleja
                                                                                        nuestro compromiso con un futuro
                                                                                        más sostenible y vibrante para
                                                                                        todos.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </b-card-body>
                                                            </b-collapse>
                                                        </b-card>
                                                        <b-card no-body class="mb-2" style="background-color: #ebebeb">
                                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                                <b-button
                                                                    class="button-sostenibilidad"
                                                                    block
                                                                    v-b-toggle.accordion-9
                                                                    >Cambio Climático y Huella Ambiental: GAF
                                                                    Comprometido con un Futuro Sostenible</b-button
                                                                >
                                                            </b-card-header>
                                                            <b-collapse
                                                                id="accordion-9"
                                                                accordion="my-accordion2"
                                                                role="tabpanel"
                                                            >
                                                                <b-card-body>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="col-12">
                                                                                    <p class="text-justify">
                                                                                        En GAF, reconocemos la urgencia
                                                                                        de abordar el cambio climático y
                                                                                        asumimos la responsabilidad de
                                                                                        reducir nuestra huella
                                                                                        ambiental. Nos embarcamos en un
                                                                                        viaje hacia la sostenibilidad,
                                                                                        integrando prácticas
                                                                                        responsables en cada aspecto de
                                                                                        nuestro negocio.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/compromiso.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b
                                                                                            >Compromiso con la
                                                                                            Sostenibilidad Ambiental:</b
                                                                                        >
                                                                                        GAF está firmemente comprometido
                                                                                        a ser parte de la solución al
                                                                                        cambio climático. Nos esforzamos
                                                                                        por adoptar prácticas
                                                                                        empresariales sostenibles que
                                                                                        minimicen nuestro impacto en el
                                                                                        medio ambiente y contribuyan a
                                                                                        la preservación de nuestros
                                                                                        recursos naturales para las
                                                                                        generaciones futuras.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/innovacion.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b
                                                                                            >Reducción de la Huella de
                                                                                            Carbono:</b
                                                                                        >
                                                                                        Implementamos medidas concretas
                                                                                        para reducir nuestra huella de
                                                                                        carbono. Desde la eficiencia
                                                                                        energética hasta la gestión de
                                                                                        residuos, estamos en constante
                                                                                        búsqueda de oportunidades para
                                                                                        minimizar nuestro impacto
                                                                                        ambiental y promover prácticas
                                                                                        comerciales más ecológicas.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/integridad.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b
                                                                                            >Innovación en Soluciones
                                                                                            Sostenibles:</b
                                                                                        >
                                                                                        En GAF, entendemos que la
                                                                                        innovación es clave para abordar
                                                                                        los desafíos ambientales.
                                                                                        Desarrollamos y promovemos
                                                                                        soluciones tecnológicas que no
                                                                                        solo cumplen con los estándares
                                                                                        ambientales, sino que también
                                                                                        contribuyen activamente a la
                                                                                        mitigación y adaptación al
                                                                                        cambio climático.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-center justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/integridad.png"
                                                                                        class="img-fluid w-40"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10">
                                                                                    <p class="text-justify">
                                                                                        <b
                                                                                            >Concientización y
                                                                                            Educación:</b
                                                                                        >
                                                                                        Nos esforzamos por aumentar la
                                                                                        conciencia sobre el cambio
                                                                                        climático y la importancia de
                                                                                        reducir la huella ambiental.
                                                                                        Educamos a nuestros
                                                                                        colaboradores, clientes y
                                                                                        comunidades sobre prácticas
                                                                                        sostenibles, promoviendo un
                                                                                        cambio colectivo hacia un futuro
                                                                                        más verde.
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-12">
                                                                                    <p class="text-justify">
                                                                                        En <b>GAF</b>, no solo nos
                                                                                        adaptamos a las demandas del
                                                                                        presente, sino que también
                                                                                        trabajamos incansablemente para
                                                                                        crear un legado ambientalmente
                                                                                        responsable. A través de
                                                                                        acciones tangibles y una
                                                                                        mentalidad proactiva, estamos
                                                                                        construyendo un camino hacia un
                                                                                        futuro donde la sostenibilidad
                                                                                        sea el núcleo de nuestras
                                                                                        operaciones y un legado que
                                                                                        dejamos para las generaciones
                                                                                        venideras.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </b-card-body>
                                                            </b-collapse>
                                                        </b-card>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </b-card-body>
                            </b-collapse>
                        </b-card>
                    </div>
                </section>
            </div>
        </div>
        <section class="container subscriptions pb-5 px-2 img-responsive" style="background-color: #ebebeb;">
            <div class="accordion" role="tablist">
                <b-card no-body class="card-buttom" style="background-color: #ebebeb">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button
                            class="button-selectres"
                            style="border-top: none !important; background-color: #ebebeb;"
                            block
                            v-b-toggle.accordion-1
                        >
                            <div
                                class="d-flex align-items-center justify-content-left ml-4"
                                style="padding-bottom: 12px"
                            >
                                <div class="line"></div>
                                <img src="/img/quienesomos.png" class="img-fluid w-10 cont-imgres" />
                                <span class="p-2 text-left" style="min-width: 100px"> Historia GAF </span>
                            </div>
                        </b-button>
                    </b-card-header>
                    <b-collapse id="accordion-1" accordion="my-accordion" role="tabpanel">
                        <b-card-body class="p-0">
                            <div class="row div-contres">
                                <div class="col-12 p-3">
                                    <div class="row">
                                        <div
                                            class="col-12 text-center icon-int d-flex align-items-center justify-content-left"
                                        >
                                            <div
                                                class="text-center d-flex align-items-center justify-content-center"
                                                style="padding-left: 7px"
                                            >
                                                <img src="/img/quienessomosactive.png" class="img-fluid w-25" />
                                                <h3 class="pl-3"><b>Historia GAF</b></h3>
                                            </div>
                                        </div>
                                        <div class="col-12 pr-0">
                                            <p class="text-justify" style="line-height: 1.3">
                                                <b>GAF</b> avanza con determinaci&oacute;n en el desarrollo de nuestra
                                                estrategia corporativa, centrados en forjar un modelo de negocio
                                                sostenible y rentable. Nos dedicamos a contribuir de manera inclusiva a
                                                la prosperidad de nuestro pa&iacute;s, mejorando la calidad de vida de
                                                las personas y fomentando un impacto positivo en el medio ambiente. Para
                                                alcanzar estos objetivos, <b>GAF</b> ha dise&ntilde;ado e implementado
                                                diversas iniciativas destinadas a fortalecer nuestra propuesta de valor,
                                                aumentar la productividad de la empresa y agilizar la toma de
                                                decisiones.
                                            </p>
                                        </div>
                                        <div class="col-12 pr-0">
                                            <img src="/img/gafhistory.png" class="img-fluid w-80" />
                                        </div>
                                        <div class="col-12 pr-0">
                                            <p class="text-justify" style="line-height: 1.3">
                                                Estamos acelerando nuestra transformaci&oacute;n digital al ofrecer
                                                soluciones para el mercado de An&aacute;lisis de Mercado Inteligente
                                                <b>(AMI)</b> y la Herramienta Ejecutora de Gestiones Operativas y
                                                Comerciales <b>(HEGO).</b> Estos proyectos se dividen en cuatro fases
                                                clave:
                                            </p>
                                        </div>
                                        <div class="col-12 pr-0">
                                            <p class="text-justify" style="line-height: 1.3">
                                                <b>Planeacion:</b> Establecemos s&oacute;lidas bases estrat&eacute;gicas
                                                para cada iniciativa.<br /><b>Ejecucion:</b> Implementamos las
                                                soluciones de manera efectiva y eficiente.<br /><b
                                                    >Aseguramiento de valor:</b
                                                >
                                                Evaluamos y garantizamos el impacto y la eficacia de nuestras
                                                acciones.<br /><b>(RRHH):</b> Fomentamos un entorno laboral que potencia
                                                el desarrollo de nuestro equipo, asegurando que est&eacute;n alineados
                                                con nuestros objetivos y valores.<br />
                                                <br />Cada una de estas categor&iacute;as est&aacute; respaldada por
                                                mecanismos de seguimiento y monitoreo, como cronogramas, indicadores y
                                                mediciones de impacto, para asegurar el &eacute;xito continuo de
                                                nuestras iniciativas y cumplir con nuestro compromiso de crecimiento
                                                responsable y sostenible.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="card-buttom"  style="background-color: #ebebeb">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button
                            class="button-selectres"
                            style="border-top: none !important; background-color: #ebebeb;"
                            block
                            v-b-toggle.accordion-2
                        >
                            <div
                                class="d-flex align-items-center justify-content-left ml-4"
                                style="padding-bottom: 12px; padding-top: 11px"
                            >
                                <div class="line"></div>
                                <img src="/img/quienesomos.png" class="img-fluid w-10 cont-imgres" />
                                <span class="p-2 text-left" style="min-width: 100px"> ¿Quiénes somos? </span>
                            </div>
                        </b-button>
                    </b-card-header>
                    <b-collapse id="accordion-2" accordion="my-accordion" role="tabpanel">
                        <b-card-body class="p-0">
                            <div class="row div-contres">
                                <div class="col-12 p-3">
                                    <div class="row">
                                        <div
                                            class="col-12 text-center icon-int d-flex align-items-center justify-content-left"
                                        >
                                            <div class="text-center d-flex align-items-center justify-content-center">
                                                <img src="/img/quienessomosactive.png" class="img-fluid w-25" />
                                                <div style="padding-top: 10px" class="pl-3">
                                                    <h6 class="m-0 d-flex justify-content-start"><b>¿Quiénes</b></h6>
                                                    <h3><b>Somos?</b></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pr-0">
                                            <p class="text-justify" style="line-height: 1.3">
                                                En <b>GAF</b>, Innovamos el Futuro Financiero:<br /><br /><b
                                                    >Soluciones Fintech para el &Eacute;xito Sostenible</b
                                                ><br />
                                                <br />Nos destacamos como l&iacute;deres en el sector Fintech,
                                                especializados en procesos clave que abarcan la
                                                <b
                                                    >elegibilidad, coadministraci&oacute;n, incorporaci&oacute;n y
                                                    recaudo master.</b
                                                >
                                                Nuestro enfoque se centra en brindar soluciones excepcionales
                                                dise&ntilde;adas espec&iacute;ficamente para originadores de libranza en
                                                el sector p&uacute;blico y fondos de inversi&oacute;n, asegurando la
                                                administraci&oacute;n efectiva de sus activos y mitigando los riesgos
                                                m&aacute;s cr&iacute;ticos identificados por GAF.<br />
                                            </p>
                                        </div>
                                        <div class="col-12 pr-0">
                                            <img src="/img/gafhistory.png" class="img-fluid w-80" />
                                        </div>
                                        <div class="col-12 pr-0">
                                            <p class="text-justify" style="line-height: 1.3">
                                                <br />Nuestra dedicaci&oacute;n inquebrantable a la excelencia y la
                                                innovaci&oacute;n nos ha consolidado como un socio confiable y efectivo
                                                para las compa&ntilde;&iacute;as que buscan transformar y optimizar sus
                                                operaciones financieras. En GAF, no solo ofrecemos servicios; ofrecemos
                                                un camino hacia el &eacute;xito sostenible en el din&aacute;mico mundo
                                                financiero actual.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="card-buttom" style="background-color: #ebebeb">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button
                            class="button-selectres"
                            style="border-top: none !important; background-color: #ebebeb;"
                            block
                            v-b-toggle.accordion-3
                        >
                            <div
                                class="d-flex align-items-center justify-content-left ml-4"
                                style="padding-bottom: 12px; padding-top: 11px"
                            >
                                <div class="line"></div>
                                <img src="/img/quienesomos.png" class="img-fluid w-10 cont-imgres" />
                                <span class="p-2 text-left" style="min-width: 100px"> ¿Donde estamos? </span>
                            </div>
                        </b-button>
                    </b-card-header>
                    <b-collapse id="accordion-3" accordion="my-accordion" role="tabpanel">
                        <b-card-body class="p-0">
                            <div class="row div-contres">
                                <div class="col-12 p-3">
                                    <div class="row">
                                        <div
                                            class="col-12 text-center icon-int d-flex align-items-center justify-content-left"
                                        >
                                            <div class="text-center d-flex align-items-center justify-content-center">
                                                <img src="/img/dondeestamosactive.png" class="img-fluid w-25" />
                                                <div style="padding-top: 10px" class="pl-3">
                                                    <h6 class="m-0 d-flex justify-content-start"><b>¿Donde</b></h6>
                                                    <h3><b>Estamos?</b></h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 pr-0">
                                            <div class="row">
                                                <div
                                                    class="col-12 pr-0 d-flex align-items-center justify-content-center"
                                                >
                                                    <iframe
                                                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.911097495013!2d-76.54234542673285!3d3.3719061517464857!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e30a17a4d89d2f7%3A0x3543b74a3e50b507!2sHolguines%20Trade%20Center%20Centro%20Comercial!5e0!3m2!1ses-419!2sco!4v1702676618990!5m2!1ses-419!2sco"
                                                        width="790"
                                                        height="400"
                                                        style="
                                                            border: 0;
                                                            border-radius: 15px;
                                                            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
                                                        "
                                                        allowfullscreen=""
                                                        loading="lazy"
                                                        referrerpolicy="no-referrer-when-downgrade"
                                                    ></iframe>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="card-buttom" style="background-color: #ebebeb;">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button
                            class="button-selectres"
                            style="border-top: none !important; background-color: #ebebeb;"
                            block
                            v-b-toggle.accordion-4
                        >
                            <div
                                class="d-flex align-items-center justify-content-left ml-4"
                                style="padding-bottom: 12px; padding-top: 11px"
                            >
                                <div class="line"></div>
                                <img src="/img/valores.png" class="img-fluid w-10 cont-imgres" />
                                <span class="p-2 text-left" style="min-width: 100px"> Nuestros valores </span>
                            </div>
                        </b-button>
                    </b-card-header>
                    <b-collapse id="accordion-4" accordion="my-accordion" role="tabpanel">
                        <b-card-body class="p-0">
                            <div class="row div-contres">
                                <div class="col-12 p-3">
                                    <div class="row">
                                        <div
                                            class="col-12 text-center icon-int d-flex align-items-center justify-content-left"
                                        >
                                            <div class="text-center d-flex align-items-center justify-content-center">
                                                <img src="/img/valoresactive.png" class="img-fluid w-25" />
                                                <div style="padding-top: 10px" class="pl-3">
                                                    <h6 class="m-0 d-flex justify-content-start"><b>Nuestros</b></h6>
                                                    <h3><b>Valores</b></h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 pr-0">
                                            <div class="row">
                                                <div class="col-2 d-flex align-items-start justify-content-center">
                                                    <img src="/img/compromiso.png" class="img-fluid w-100" />
                                                </div>
                                                <div class="col-10 pr-0 d-flex align-items-center justify-content-left">
                                                    <p class="text-justify" style="line-height: 1.3">
                                                        <b>Compromiso con la Excelencia:</b> Buscamos constantemente la
                                                        perfecci&oacute;n en cada detalle, desde la elegibilidad hasta
                                                        el recaudo master, para asegurar la satisfacci&oacute;n total de
                                                        nuestros clientes.
                                                    </p>
                                                </div>
                                                <div class="col-2 d-flex align-items-start justify-content-center">
                                                    <img src="/img/innovacion.png" class="img-fluid w-100" />
                                                </div>
                                                <div class="col-10 pr-0 d-flex align-items-center justify-content-left">
                                                    <p class="text-justify" style="line-height: 1.3">
                                                        <b>Innovaci&oacute;n Sostenible:</b> Nos esforzamos por liderar
                                                        la industria con soluciones creativas y sostenibles que se
                                                        adapten a las cambiantes din&aacute;micas del mercado
                                                        financiero.
                                                    </p>
                                                </div>
                                                <div class="col-2 d-flex align-items-start justify-content-center">
                                                    <img src="/img/integridad.png" class="img-fluid w-100" />
                                                </div>
                                                <div class="col-10 pr-0 d-flex align-items-center justify-content-left">
                                                    <p class="text-justify" style="line-height: 1.3">
                                                        <b>Integridad y Transparencia:</b> Basamos nuestras relaciones
                                                        comerciales en la honestidad y la transparencia, construyendo
                                                        confianza duradera con nuestros clientes y socios.
                                                    </p>
                                                </div>
                                                <div class="col-2 d-flex align-items-start justify-content-center">
                                                    <img src="/img/enfoque.png" class="img-fluid w-100" />
                                                </div>
                                                <div class="col-10 pr-0 d-flex align-items-center justify-content-left">
                                                    <p class="text-justify" style="line-height: 1.3">
                                                        <b>Enfoque en el Cliente:</b> Colocamos las necesidades y
                                                        objetivos de nuestros clientes en el centro de todo lo que
                                                        hacemos, adaptando nuestras soluciones para satisfacer sus
                                                        desaf&iacute;os espec&iacute;ficos.
                                                    </p>
                                                </div>
                                                <div class="col-2 my-3 d-flex align-items-start justify-content-center">
                                                    <img src="/img/personas.png" class="img-fluid w-100" />
                                                </div>
                                                <div class="col-10 pr-0 d-flex align-items-center justify-content-left">
                                                    <p class="text-justify" style="line-height: 1.3">
                                                        <b>Personas:</b> Reconocemos que cada persona es esencial para
                                                        nuestro &eacute;xito.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body class="card-buttom" style="background-color: #ebebeb;">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button
                            class="button-selectres"
                            style="border-top: none !important; background-color: #ebebeb;"
                            block
                            v-b-toggle.accordion-5
                        >
                            <div
                                class="d-flex align-items-center justify-content-left ml-4"
                                style="padding-bottom: 12px; padding-top: 11px"
                            >
                                <div class="line"></div>
                                <img src="/img/target.png" class="img-fluid w-10 cont-imgres" />
                                <span class="p-2 text-left" style="min-width: 100px">Target</span>
                            </div>
                        </b-button>
                    </b-card-header>
                    <b-collapse id="accordion-5" accordion="my-accordion" role="tabpanel">
                        <b-card-body class="p-0">
                            <div class="row div-contres">
                                <div class="col-12 p-3">
                                    <div class="row">
                                        <div
                                            class="col-12 text-center icon-int d-flex align-items-center justify-content-left"
                                        >
                                            <div class="text-center d-flex align-items-center justify-content-center">
                                                <img src="/img/targetactive.png" class="img-fluid w-25" />
                                                <div style="padding-top: 10px" class="pl-3">
                                                    <h3><b>Target</b></h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 pr-0">
                                            <div class="row d-flex align-items-center justify-content-center">
                                                <div class="col-12 mb-4 div-boxes">
                                                    <div class="row target-div">
                                                        <div
                                                            class="col-4 px-0 text-center d-flex align-items-center justify-content-start"
                                                        >
                                                            <img src="/img/target1.png" style="width: 106px" />
                                                        </div>
                                                        <div class="col-8 pr-0 d-flex align-items-center">
                                                            <p
                                                                class="my-1 ml-1"
                                                                style="line-height: 1.3; font-size: 12px"
                                                            >
                                                                <b>Principales:</b><br />-Fondos De Capital Privado.<br />-Originadores
                                                                de Créditos.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-4 div-boxes">
                                                    <div class="row target-div">
                                                        <div
                                                            class="col-4 px-0 text-center d-flex align-items-center justify-content-start"
                                                        >
                                                            <img src="/img/target2.png" style="width: 106px" />
                                                        </div>
                                                        <div class="col-8 pr-0 d-flex align-items-center">
                                                            <p
                                                                class="my-1 ml-1"
                                                                style="line-height: 1.3; font-size: 12px"
                                                            >
                                                                <b>Otros:</b><br />-Gestor De Activos Alternativos.<br />-Agencias
                                                                De Cobranza.
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mb-4">
                                                    <div class="row">
                                                        <div
                                                            class="col-4 pr-0 text-center d-flex align-items-center justify-content-center"
                                                        >
                                                            <img src="/img/LogoCK.png" class="img-fluid w-50" />
                                                        </div>
                                                        <div
                                                            class="col-4 text-center d-flex align-items-center justify-content-center"
                                                        >
                                                            <img src="/img/trlogo.png" class="img-fluid w-100" />
                                                        </div>
                                                        <div
                                                            class="col-4 pr-0 text-center d-flex align-items-center justify-content-center"
                                                        >
                                                            <img src="/img/kblogo.png" class="img-fluid w-100" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 pr-0">
                                                    <p class="text-justify" style="line-height: 1.3">
                                                        Nuestra experiencia abarca desde la personalización de
                                                        soluciones para el día a día de los originadores de créditos y
                                                        fondos de inversión hasta atender las necesidades específicas de
                                                        gestores de
                                                        <b>NPLs (Non-Performing Loans).</b>En GAF, estamos comprometidos
                                                        con proporcionar servicios de vanguardia que permitan a nuestros
                                                        clientes avanzar y estabilizarse con confianza en un mundo
                                                        financiero en constante evolución.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>

                <b-card no-body style="background-color: #ebebeb;">
                    <b-card-header header-tag="header" class="p-1" role="tab">
                        <b-button
                            class="button-selectres"
                            style="border-top: none !important; background-color: #ebebeb;"
                            block
                            v-b-toggle.accordion-6
                        >
                            <div
                                class="d-flex align-items-center justify-content-left ml-4"
                                style="padding-bottom: 12px; padding-top: 11px"
                            >
                                <div class="line"></div>
                                <img src="/img/sostenibilidad.png" class="img-fluid w-10 cont-imgres" />
                                <span class="p-2 text-left" style="min-width: 100px"> Sostenibilidad </span>
                            </div>
                        </b-button>
                    </b-card-header>
                    <b-collapse id="accordion-6" accordion="my-accordion" role="tabpanel">
                        <b-card-body class="p-0">
                            <div class="row div-contres">
                                <div class="col-12 p-3">
                                    <div class="row">
                                        <div
                                            class="col-12 text-center icon-int d-flex align-items-center justify-content-left"
                                        >
                                            <div class="text-center d-flex align-items-center justify-content-center">
                                                <img
                                                    src="/img/sostenibilidadactive.png"
                                                    class="img-fluid w-25"
                                                    style="margin-left: 40px"
                                                />
                                                <div style="padding-top: 10px" class="pl-3">
                                                    <h3><b>Sostenibilidad</b></h3>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 pr-0">
                                            <div class="row">
                                                <div class="col-12 pr-0">
                                                    <p class="text-justify" style="line-height: 1.3">
                                                        Impuslsando el Crecimiento Sostenible a Nivel Global <br />

                                                        <br />En GAF, damos un paso adelante hacia el futuro,
                                                        empoderando a empresas y economias en todo el mundo a
                                                        trav&eacute;s de nuestro firme comprimiso con la sostenibilidad,
                                                        una piedra angular de nuestra cultura y operaciones. Al medir,
                                                        gestionar, controlar y divulgar de manera transparente el
                                                        impacto global de nuestros esfuerzos, construimos no solo un
                                                        equipo de trabajo s&oacute;lido, sino tambi&eacute;n sistemas
                                                        saludables que potencian el crecimiento a largo plazo.<br />
                                                    </p>
                                                </div>
                                                <div class="col-6 pr-0">
                                                    <p class="text-justify" style="line-height: 1.3">
                                                        Juntos, estamos creando un impacto positivo que trasciende
                                                        fronteras y frtalece el tejido Social y ambiental para las
                                                        generaciones venideras. En GAF , la sostenibilidad no es solo
                                                        una iniciativa, sino un compromiso arraigado en cada aspecto de
                                                        nuestro quehacer diario.
                                                    </p>
                                                </div>
                                                <div
                                                    class="col-6 pr-0 d-flex align-items-center justify-content-center"
                                                >
                                                    <img src="/img/imgsostenibilidad.png" style="width: 170px" />
                                                </div>
                                                <div class="col-12 pr-0">
                                                    <div class="accordion" role="tablist">
                                                        <b-card no-body class="mb-2" style="background-color: #ebebeb;">
                                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                                <b-button
                                                                    class="button-sostenibilidad"
                                                                    style="font-size: 12px"
                                                                    block
                                                                    v-b-toggle.accordion-7
                                                                    >Seguridad, Gobernanza y Cumplimiento: Pilares de
                                                                    Confianza en GAF</b-button
                                                                >
                                                            </b-card-header>
                                                            <b-collapse
                                                                id="accordion-7"
                                                                accordion="my-accordion2"
                                                                role="tabpanel"
                                                            >
                                                                <b-card-body>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="col-12 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        En GAF, abrazamos la importancia
                                                                                        de la seguridad, gobernanza y
                                                                                        cumplimiento como pilares
                                                                                        fundamentales que sustentan la
                                                                                        confianza en todas nuestras
                                                                                        operaciones. Nuestra dedicación
                                                                                        a estos principios críticos se
                                                                                        traduce en un compromiso
                                                                                        inquebrantable con la
                                                                                        integridad, la transparencia y
                                                                                        la excelencia en cada paso.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/compromiso.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b>Seguridad:</b> Implementamos
                                                                                        rigurosas medidas de seguridad
                                                                                        para salvaguardar los datos y
                                                                                        activos de nuestros clientes.
                                                                                        Desde avanzadas tecnologías
                                                                                        hasta protocolos de gestión de
                                                                                        riesgos, garantizamos un entorno
                                                                                        seguro que protege la
                                                                                        confidencialidad y la integridad
                                                                                        de la información.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/innovacion.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b>Gobernanza:</b> La eficaz
                                                                                        gobernanza es esencial para la
                                                                                        toma de decisiones sólidas y
                                                                                        éticas. En GAF, nuestra
                                                                                        estructura de gobernanza
                                                                                        promueve la responsabilidad, la
                                                                                        transparencia y la alineación
                                                                                        con los objetivos estratégicos,
                                                                                        asegurando una dirección
                                                                                        efectiva y sostenible.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/integridad.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b>Cumplimiento:</b> Cumplir con
                                                                                        regulaciones y estándares es un
                                                                                        compromiso no negociable en GAF.
                                                                                        Mantenemos una adhesión estricta
                                                                                        a las normativas pertinentes,
                                                                                        garantizando la legalidad y
                                                                                        ética en todas nuestras
                                                                                        actividades, y ofreciendo a
                                                                                        nuestros clientes la
                                                                                        tranquilidad de trabajar
                                                                                        comprometidos con la integridad
                                                                                        en los resultados.
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-12 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        Desde el ámbito tecnológico
                                                                                        hasta la gestión de datos y más
                                                                                        allá, en GAF, entendemos que la
                                                                                        confianza se construye a través
                                                                                        de la seguridad robusta, una
                                                                                        gobernanza efectiva y el
                                                                                        cumplimiento diligente. Estos
                                                                                        principios no solo son parte de
                                                                                        nuestra estructura, sino que
                                                                                        también son la base sobre la
                                                                                        cual construimos relaciones
                                                                                        sólidas y exitosas con nuestros
                                                                                        clientes y socios.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </b-card-body>
                                                            </b-collapse>
                                                        </b-card>
                                                        <b-card no-body class="mb-2" style="background-color: #ebebeb;">
                                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                                <b-button
                                                                    class="button-sostenibilidad"
                                                                    style="font-size: 12px"
                                                                    block
                                                                    v-b-toggle.accordion-8
                                                                    >Personas, Innovación Social y Comunidades: Forjando
                                                                    un Futuro Sostenible en GAF</b-button
                                                                >
                                                            </b-card-header>
                                                            <b-collapse
                                                                id="accordion-8"
                                                                accordion="my-accordion2"
                                                                role="tabpanel"
                                                            >
                                                                <b-card-body>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="col-12 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        En GAF, reconocemos que el
                                                                                        verdadero impacto va más allá de
                                                                                        los productos y servicios; se
                                                                                        encuentra en las personas, la
                                                                                        innovación social y el tejido
                                                                                        comunitario. Nos comprometemos a
                                                                                        ser agentes de cambio, guiados
                                                                                        por la creencia de que el
                                                                                        progreso auténtico se construye
                                                                                        alrededor de estos fundamentos.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/compromiso.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b>Personas:</b> En GAF, las
                                                                                        personas son el corazón de
                                                                                        nuestra organización. Valoramos
                                                                                        la diversidad, fomentamos un
                                                                                        ambiente inclusivo y promovemos
                                                                                        el desarrollo personal y
                                                                                        profesional de nuestro equipo.
                                                                                        Creemos que la verdadera fuerza
                                                                                        de GAF reside en la pasión y el
                                                                                        talento de las personas que
                                                                                        forman parte de nuestra familia.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/innovacion.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b>Innovación Social:</b> La
                                                                                        innovación no solo impulsa
                                                                                        nuestras soluciones, sino que
                                                                                        también moldea nuestro
                                                                                        compromiso social. Buscamos
                                                                                        constantemente nuevas formas de
                                                                                        abordar desafíos sociales,
                                                                                        aplicando tecnologías y
                                                                                        estrategias innovadoras para
                                                                                        mejorar la calidad de vida y
                                                                                        fomentar la equidad en las
                                                                                        comunidades que servimos.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/integridad.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b>Comunidades:</b> En
                                                                                        <b>GAF</b>, entendemos que
                                                                                        nuestro éxito está entrelazado
                                                                                        con el bienestar de las
                                                                                        comunidades a las que
                                                                                        pertenecemos. Nos involucramos
                                                                                        activamente en iniciativas
                                                                                        comunitarias, apoyando causas
                                                                                        sociales y contribuyendo al
                                                                                        desarrollo sostenible. Creemos
                                                                                        en el poder de las empresas para
                                                                                        catalizar un cambio positivo y
                                                                                        estamos comprometidos a ser un
                                                                                        agente de transformación en las
                                                                                        comunidades que tocamos.
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-12 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        En <b>GAF</b>, nuestra visión no
                                                                                        solo está centrada en el
                                                                                        crecimiento empresarial, sino
                                                                                        también en cómo podemos mejorar
                                                                                        la vida de las personas,
                                                                                        fomentar la innovación social y
                                                                                        fortalecer las comunidades en
                                                                                        las que vivimos y trabajamos.
                                                                                        Cada paso que damos refleja
                                                                                        nuestro compromiso con un futuro
                                                                                        más sostenible y vibrante para
                                                                                        todos.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </b-card-body>
                                                            </b-collapse>
                                                        </b-card>
                                                        <b-card no-body class="mb-2" style="background-color: #ebebeb;">
                                                            <b-card-header header-tag="header" class="p-1" role="tab">
                                                                <b-button
                                                                    class="button-sostenibilidad"
                                                                    style="font-size: 12px"
                                                                    block
                                                                    v-b-toggle.accordion-9
                                                                    >Cambio Climático y Huella Ambiental: GAF
                                                                    Comprometido con un Futuro Sostenible</b-button
                                                                >
                                                            </b-card-header>
                                                            <b-collapse
                                                                id="accordion-9"
                                                                accordion="my-accordion2"
                                                                role="tabpanel"
                                                            >
                                                                <b-card-body>
                                                                    <div class="row">
                                                                        <div class="col-12">
                                                                            <div class="row">
                                                                                <div class="col-12 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        En GAF, reconocemos la urgencia
                                                                                        de abordar el cambio climático y
                                                                                        asumimos la responsabilidad de
                                                                                        reducir nuestra huella
                                                                                        ambiental. Nos embarcamos en un
                                                                                        viaje hacia la sostenibilidad,
                                                                                        integrando prácticas
                                                                                        responsables en cada aspecto de
                                                                                        nuestro negocio.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/compromiso.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b
                                                                                            >Compromiso con la
                                                                                            Sostenibilidad Ambiental:</b
                                                                                        >
                                                                                        GAF está firmemente comprometido
                                                                                        a ser parte de la solución al
                                                                                        cambio climático. Nos esforzamos
                                                                                        por adoptar prácticas
                                                                                        empresariales sostenibles que
                                                                                        minimicen nuestro impacto en el
                                                                                        medio ambiente y contribuyan a
                                                                                        la preservación de nuestros
                                                                                        recursos naturales para las
                                                                                        generaciones futuras.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/innovacion.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b
                                                                                            >Reducción de la Huella de
                                                                                            Carbono:</b
                                                                                        >
                                                                                        Implementamos medidas concretas
                                                                                        para reducir nuestra huella de
                                                                                        carbono. Desde la eficiencia
                                                                                        energética hasta la gestión de
                                                                                        residuos, estamos en constante
                                                                                        búsqueda de oportunidades para
                                                                                        minimizar nuestro impacto
                                                                                        ambiental y promover prácticas
                                                                                        comerciales más ecológicas.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/integridad.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b
                                                                                            >Innovación en Soluciones
                                                                                            Sostenibles:</b
                                                                                        >
                                                                                        En GAF, entendemos que la
                                                                                        innovación es clave para abordar
                                                                                        los desafíos ambientales.
                                                                                        Desarrollamos y promovemos
                                                                                        soluciones tecnológicas que no
                                                                                        solo cumplen con los estándares
                                                                                        ambientales, sino que también
                                                                                        contribuyen activamente a la
                                                                                        mitigación y adaptación al
                                                                                        cambio climático.
                                                                                    </p>
                                                                                </div>
                                                                                <div
                                                                                    class="col-2 d-flex align-items-start justify-content-center"
                                                                                >
                                                                                    <img
                                                                                        src="/img/integridad.png"
                                                                                        class="img-fluid w-100"
                                                                                    />
                                                                                </div>
                                                                                <div class="col-10 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        <b
                                                                                            >Concientización y
                                                                                            Educación:</b
                                                                                        >
                                                                                        Nos esforzamos por aumentar la
                                                                                        conciencia sobre el cambio
                                                                                        climático y la importancia de
                                                                                        reducir la huella ambiental.
                                                                                        Educamos a nuestros
                                                                                        colaboradores, clientes y
                                                                                        comunidades sobre prácticas
                                                                                        sostenibles, promoviendo un
                                                                                        cambio colectivo hacia un futuro
                                                                                        más verde.
                                                                                    </p>
                                                                                </div>
                                                                                <div class="col-12 pr-0">
                                                                                    <p
                                                                                        class="text-justify"
                                                                                        style="line-height: 1.3"
                                                                                    >
                                                                                        En <b>GAF</b>, no solo nos
                                                                                        adaptamos a las demandas del
                                                                                        presente, sino que también
                                                                                        trabajamos incansablemente para
                                                                                        crear un legado ambientalmente
                                                                                        responsable. A través de
                                                                                        acciones tangibles y una
                                                                                        mentalidad proactiva, estamos
                                                                                        construyendo un camino hacia un
                                                                                        futuro donde la sostenibilidad
                                                                                        sea el núcleo de nuestras
                                                                                        operaciones y un legado que
                                                                                        dejamos para las generaciones
                                                                                        venideras.
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </b-card-body>
                                                            </b-collapse>
                                                        </b-card>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </b-card-body>
                    </b-collapse>
                </b-card>
            </div>
        </section>
    </div>
</template>

<script>
import { ListIcon, FacebookIcon, InstagramIcon, YoutubeIcon, TwitterIcon } from '../icons';

export default {
    components: {
        ListIcon,
        FacebookIcon,
        InstagramIcon,
        YoutubeIcon,
        TwitterIcon
    }
};
</script>
<style>
.card-header {
    padding: 0 !important;
    margin-bottom: 0;
    border-bottom: none !important;
    background-color: #fff !important;
}
.div-img {
    border-right: solid 2px #d3d3d3;
}
.cont-img {
    border-right: solid 2px #d3d3d3;
    padding: 12px 15px;
}

.cont-imgres {
    /* border-left: solid 2px #d3d3d3;
    padding: 12px 5px; */
    position: relative;
    right: 5.2%;
    top: 50%;
}

.line {
    height: 63px;
    width: 2px;
    background-color: #d3d3d3;
    max-width: 12px;
    padding-right: 1px;
    position: absolute;
}

.card {
    border: none;
}
.card-buttom {
    border-bottom: 2px solid #d3d3d3 !important;
}
.target-div {
    border: 2px solid #07edb1;
    border-radius: 15px;
    padding: 10px;
    background-color: #f5f5f5;
    font-size: 14px;
}
.div-boxes {
    padding: 0 25px;
}
.icon-int {
    margin-bottom: 15px;
    margin-left: -57px;
}
.div-contres {
    margin-left: 24px;
    margin-right: 35px;
    border-left: 2px solid #d3d3d3;
}
.button-select {
    text-align: left;
    background: #fff;
    color: #000;
    font-weight: bold;
    padding: 0;
    border-radius: 0;
    border-bottom: 2px solid #d3d3d3;
    border-left: none;
    border-right: none;
}
.button-selectres {
    text-align: left;
    background: #fff;
    color: #000;
    font-weight: bold;
    padding: 0;
    border-radius: 0;
    border-bottom: 2px solid #d3d3d3;
    border-left: none;
    border-right: none;
}
.button-sostenibilidad {
    text-align: left;
    background: #000;
    color: #fff;
    font-weight: bold;
    padding: 10px 20px;
    border-radius: 48px;
}
.button-sostenibilidad:hover {
    background: #07edb1 !important;
    color: #000 !important;
    border-color: none !important;
}
.button-sostenibilidad:active {
    background: #07edb1 !important;
    color: #000 !important;
    border-color: none !important;
}
.button-sostenibilidad:focus {
    background: #07edb1 !important;
    color: #000 !important;
    border-color: none !important;
}
.button-sostenibilidad:not(:disabled):not(.disabled):active,
.button-sostenibilidad:not(:disabled):not(.disabled).active,
.show > .button-sostenibilidad.dropdown-toggle {
    background: #07edb1 !important;
    color: #000 !important;
    border-color: none !important;
}
.btn-secondary:active {
    color: #000;
    background-color: #fff;
    border-color: none;
}
.btn-secondary:hover {
    color: #000;
    background-color: #fff;
    border-color: #d3d3d3;
}
.btn-secondary:focus {
    color: #000;
    background-color: #fff;
    border-color: #d3d3d3;
}
.btn-secondary:not(:disabled):not(.disabled):active,
.btn-secondary:not(:disabled):not(.disabled).active,
.show > .btn-secondary.dropdown-toggle {
    color: #000;
    background-color: #fff;
    border-color: #d3d3d3;
}
.degradado{
  background: linear-gradient(90deg, #11484b, #0dbf95e1, #11484b, #0dbf95e1);
  -webkit-background-clip: text;
  color: transparent;  
}
.btn-informacion:hover{
  color: rgb(9, 186, 139);
}
.marg-resp{
    height: 50vh;
    z-index: 1;
    background-image: url('/img/background-home.png');
    background-size: cover;
    background-repeat: no-repeat;

    @media screen and (min-width: 800px){
        height: 35vh;
        background-size: contain;
        background-repeat: no-repeat;
    }
    @media screen and (min-width: 1000px){
        height: 90vh;
        background-size: contain;
        background-repeat: no-repeat;
    }
}
.text1 {
    max-width: 540px;
    text-align: justify;
    margin-top: 10px;
}
.end-section{
    z-index: 1;
    margin-top: 0;
    margin-top: -65px;
    @media screen and (min-width: 800px){
        margin-top: -95px;
    }
}
.img-nosotros{
    height: 247px; 
    width: 100%;
    background-image: url(/img/grey-background-responsive.png);
    background-size: cover;
    background-repeat: no-repeat;
    @media screen and (min-width: 800px){
        background-image: url(/img/grey-background2.png);
    }
}
</style>
