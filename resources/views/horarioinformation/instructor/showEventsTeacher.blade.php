<div class="offcanvas offcanvas-end offcanvas-size" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
  <div class="offcanvas-header offcanvas-header-edit">
        <div id="offcanvasRightLabel" class="container">

        </div>
    </div>
    <div class="offcanvas-body">
        <div class="container">
            <div class="row">
                <div class="col-md-12 title-event">
                    <div class="row">
                        <div class="col-md-4 title">
                            <h1>Eventos programados <i class="fa-solid fa-file-pen"></i></h1>
                        </div>
                        <div class="col-md-8 container-hours">

                            <div class='div-container-hours-select'>
                                <select name="selectMonths" id="selectMonths">
                                    <option value="null" selected disabled>Seleccione un mes</option>
                                </select>
                            </div>
                            <div class='div-container-hours'>
                                <div class='div-information-1'>
                                    <div class='div-container-A'>
                                        <p class = 'title-hours'>Horas laborales trimestrales maximas</p>
                                        <div class="text-hours">
                                            <p class="hours" id="hoursQuarter"></p>
                                            <p>horas</p>
                                        </div>
                                    </div>
                                    <div class='div-container-B'>
                                        <p class = 'title-hours'>Horas laborales mensuales maximas</p>
                                        <div class="text-hours">
                                            <p class="hours" id="hoursMonth"></p>
                                            <p>horas</p>
                                        </div>
                                    </div>
                                </div>

                                <div class='div-container-information-hours div-information'>
                                    <p class = 'title-hours'>Horas Disponibles</p>
                                    <div class="text-hours">
                                        <p class="hours" id="available">0</p>
                                        <p>horas</p>
                                    </div>
                                </div>
                                <div></div>
                                <div class='div-container-information-hours div-information'>
                                    <p class = 'title-hours'>Horas Ocupadas</p>
                                    <div class="text-hours">
                                        <p class="hours" id="busy">0</p>
                                        <p>horas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="programacion-instructor row col-12"></div>
            </div>
        </div>
    </div>
</div>
