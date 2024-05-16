import "./bootstrap";
import "../sass/app.scss";
import * as bootstrap from "bootstrap";

// // JQUERY
// import $ from 'jquery';
// window.$ = window.jQuery = $;

// // DATATABLES
// import DataTable from 'datatables.net';
// window.DataTable = DataTable;

// AXIOS
import axios from 'axios';
window.axios = axios;

// CALENDAR
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';

window.Calendar = Calendar;
window.dayGridPlugin = dayGridPlugin;
window.timeGridPlugin = timeGridPlugin;
window.listPlugin = listPlugin;
window.interactionPlugin = interactionPlugin;

// MOMENT
import moment from 'moment';
window.moment = moment;

// SWEET ALERT2  
import Swal from 'sweetalert2';
window.Swal = Swal;

// SELECT2
import 'select2';
window.$ = window.jQuery = $;

// FLATPICKR
import flatpickr from 'flatpickr';
window.flatpickr = flatpickr;
