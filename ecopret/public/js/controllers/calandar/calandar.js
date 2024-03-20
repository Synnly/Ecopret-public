const calendar = document.querySelector(".calendar"),
  date = document.querySelector(".date"),
  daysContainer = document.querySelector(".days"),
  prev = document.querySelector(".prev"),
  next = document.querySelector(".next"),
  todayBtn = document.querySelector(".today-btn"),
  gotoBtn = document.querySelector(".goto-btn"),
  dateInput = document.querySelector(".date-input"),
  eventDay = document.querySelector(".event-day"),
  eventDate = document.querySelector(".event-date"),
  eventsContainer = document.querySelector(".events"),
  addEventBtn = document.querySelector(".add-event"),
  addEventWrapper = document.querySelector(".add-event-wrapper "),
  addEventCloseBtn = document.querySelector(".close "),
  addEventFrom = document.querySelector(".event-time-from "),
  addEventTo = document.querySelector(".event-time-to "),
  addEventSubmit = document.querySelector(".add-event-btn "),
  addEventAllDay = document.querySelector(".event-time-all "),
  select = document.querySelector("select"),
  nbRecursion = document.querySelector(".nb-recursion-input");
  stockInfos = document.querySelector(".infos ");
  

let today = new Date();
let activeDay;
let month = today.getMonth();
let year = today.getFullYear();

const months = [
  "Janvier",
  "Février",
  "Mars",
  "Avril",
  "Mai",
  "Juin",
  "Juilet",
  "Aout",
  "Septembre",
  "Octobre",
  "Novembre",
  "Decembre",
];

// const eventsArr = [
//   {
//     day: 13,
//     month: 11,
//     year: 2022,
//     events: [
//       {
//         title: "Event 1 lorem ipsun dolar sit genfa tersd dsad ",
//         time: "10:00 AM",
//       },
//       {
//         title: "Event 2",
//         time: "11:00 AM",
//       },
//     ],
//   },
// ];

const eventsArr = [];

//function to add days in days with class day and prev-date next-date on previous month and next month days and active on today
function initCalendar() {
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const prevLastDay = new Date(year, month, 0);
  const prevDays = prevLastDay.getDate();
  const lastDate = lastDay.getDate();
  const day = firstDay.getDay();
  const nextDays = 7 - lastDay.getDay() - 1;

  date.innerHTML = months[month] + " " + year;

  let days = "";

  for (let x = day; x > 0; x--) {
    days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
  }

  for (let i = 1; i <= lastDate; i++) {
    //check if event is present on that day
    let event = false;
    eventsArr.forEach((eventObj) => {
      if (
        eventObj.day === i &&
        eventObj.month === month + 1 &&
        eventObj.year === year
      ) {
        event = true;
      }
    });
    if (i === new Date().getDate() && year === new Date().getFullYear() && month === new Date().getMonth() ) {
      activeDay = i;
      getActiveDay(i);
      updateEvents(i);
      if (event) {
        days += `<div class="day today active event">${i}</div>`;
      } else {
        days += `<div class="day today active">${i}</div>`;
      }
    } else {
      if (event) {
        days += `<div class="day event">${i}</div>`;
      } else {
        days += `<div class="day ">${i}</div>`;
      }
    }
  }

  for (let j = 1; j <= nextDays; j++) {
    days += `<div class="day next-date">${j}</div>`;
  }
  daysContainer.innerHTML = days;
  addListner();
}

//function to add month and year on prev and next button
function prevMonth() {
  month--;
  if (month < 0) {
    month = 11;
    year--;
  }
  initCalendar();
}

function nextMonth() {
  month++;
  if (month > 11) {
    month = 0;
    year++;
  }
  initCalendar();
}

prev.addEventListener("click", prevMonth);
next.addEventListener("click", nextMonth);

initCalendar();

//function to add active on day
function addListner() {
  const days = document.querySelectorAll(".day");
  days.forEach((day) => {
    day.addEventListener("click", (e) => {
      getActiveDay(e.target.innerHTML);
      updateEvents(Number(e.target.innerHTML));
      activeDay = Number(e.target.innerHTML);
      //remove active
      days.forEach((day) => {
        day.classList.remove("active");
      });
      //if clicked prev-date or next-date switch to that month
      if (e.target.classList.contains("prev-date")) {
        prevMonth();
        //add active to clicked day afte month is change
        setTimeout(() => {
          //add active where no prev-date or next-date
          const days = document.querySelectorAll(".day");
          days.forEach((day) => {
            if (
              !day.classList.contains("prev-date") &&
              day.innerHTML === e.target.innerHTML
            ) {
              day.classList.add("active");
            }
          });
        }, 100);
      } else if (e.target.classList.contains("next-date")) {
        nextMonth();
        //add active to clicked day afte month is changed
        setTimeout(() => {
          const days = document.querySelectorAll(".day");
          days.forEach((day) => {
            if (
              !day.classList.contains("next-date") &&
              day.innerHTML === e.target.innerHTML
            ) {
              day.classList.add("active");
            }
          });
        }, 100);
      } else {
        e.target.classList.add("active");
      }
    });
  });
}

todayBtn.addEventListener("click", () => {
  today = new Date();
  month = today.getMonth();
  year = today.getFullYear();
  initCalendar();
});

dateInput.addEventListener("input", (e) => {
  dateInput.value = dateInput.value.replace(/[^0-9/]/g, "");
  if (dateInput.value.length === 2) {
    dateInput.value += "/";
  }
  if (dateInput.value.length > 7) {
    dateInput.value = dateInput.value.slice(0, 7);
  }
  if (e.inputType === "deleteContentBackward") {
    if (dateInput.value.length === 3) {
      dateInput.value = dateInput.value.slice(0, 2);
    }
  }
});

gotoBtn.addEventListener("click", gotoDate);

function gotoDate() {
  const dateArr = dateInput.value.split("/");
  if (dateArr.length === 2) {
    if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length === 4) {
      month = dateArr[0] - 1;
      year = dateArr[1];
      initCalendar();
      return;
    }
  }
  alert("Date invalide");
}

//function get active day day name and date and update eventday eventdate
function getActiveDay(date) {
  const day = new Date(year, month, date);
  const joursSemaineFR = {
    "Sun": "Dimanche",
    "Mon": "Lundi",
    "Tue": "Mardi",
    "Wed": "Mercredi",
    "Thu": "Jeudi",
    "Fri": "Vendredi",
    "Sat": "Samedi"
  };
  const dayNameEN = day.toString().split(" ")[0];
  const dayName = joursSemaineFR[dayNameEN];
  eventDay.innerHTML = dayName;
  eventDate.innerHTML = date + " " + months[month] + " " + year;
}

//function update events when a day is active
function updateEvents(date) {
  let events = "";
  eventsArr.forEach((event) => {
    if ( date === event.day && month + 1 === event.month && year === event.year ) {
      event.events.forEach((event) => {
        events += `<div class="event">
        <div class="title">
        <i class="fas fa-circle"></i>
        <h3 class="event-title">${event.title}</h3>
        </div>
        <div class="event-time">
        <span class="event-time">${event.time}</span>
        </div>
        </div>`;
      });
    }
  });
  if (events === "") {
    events = `<div class="no-event">
            <h3>Aucune Disponibilité</h3>
        </div>`;
  }
  eventsContainer.innerHTML = events;
  saveEvents();

}

//function to add event
addEventBtn.addEventListener("click", () => {
  addEventWrapper.classList.toggle("active");
});

addEventCloseBtn.addEventListener("click", () => {
  addEventWrapper.classList.remove("active");
});

document.addEventListener("click", (e) => {
  if (e.target !== addEventBtn && !addEventWrapper.contains(e.target)) {
    addEventWrapper.classList.remove("active");
  }
});

//allow only time in eventtime from and to
addEventFrom.addEventListener("input", (e) => {
  const currentValue = addEventFrom.value;
  const newValue = currentValue.replace(/[^0-9:]/g, "");

  // Vérifier si le caractère supprimé est un :
  if (currentValue.length > newValue.length && currentValue.charAt(currentValue.length - 1) !== ':') {
    // Mettre à jour la valeur en supprimant le dernier caractère
    addEventFrom.value = newValue;
    return;
  }

  // Ajouter ':' automatiquement après le 2ème caractère
  if (newValue.length === 2 && currentValue.length <= 2 && e.inputType !== 'deleteContentBackward') {
    addEventFrom.value = newValue + ":";
    return;
  }

  // Limiter la longueur à 5 caractères
  if (newValue.length > 5) {
    addEventFrom.value = newValue.slice(0, 5);
    return;
  }

  // Mettre à jour la valeur
  addEventFrom.value = newValue;
});


addEventTo.addEventListener("input", (e) => {
  const currentValue = addEventTo.value;
  const newValue = currentValue.replace(/[^0-9:]/g, "");

  // Vérifier si le caractère supprimé est un :
  if (currentValue.length > newValue.length && currentValue.charAt(currentValue.length - 1) !== ':') {
    // Mettre à jour la valeur en supprimant le dernier caractère
    addEventTo.value = newValue;
    return;
  }

  // Ajouter ':' automatiquement après le 2ème caractère
  if (newValue.length === 2 && currentValue.length <= 2 && e.inputType !== 'deleteContentBackward') {
    addEventTo.value = newValue + ":";
    return;
  }

  // Limiter la longueur à 5 caractères
  if (newValue.length > 5) {
    addEventTo.value = newValue.slice(0, 5);
    return;
  }

  // Mettre à jour la valeur
  addEventTo.value = newValue;
});

//function to add event to eventsArr
addEventSubmit.addEventListener("click", () => {
  const eventTimeFrom = addEventFrom.value;
  const eventTimeTo = addEventTo.value;
  const eventTitle = "Disponible";
  if (eventTitle === "" || eventTimeFrom === "" || eventTimeTo === "") {
    alert("Remplissez tous les champs");
    return;
  }

  //check correct time format 24 hour
  const timeFromArr = eventTimeFrom.split(":");
  const timeToArr = eventTimeTo.split(":");
  if (
    timeFromArr.length !== 2 ||
    timeToArr.length !== 2 ||
    timeFromArr[0] > 23 ||
    timeFromArr[1] > 59 ||
    timeToArr[0] > 23 ||
    timeToArr[1] > 59
  ) {
    alert("Heure invalide");
    return;
  }

  if(!dateInvalide(eventTimeFrom, eventTimeTo)){
    alert("L'heure de départ doit être plus grande que l'heure de fin");
  }else {
    var timeFrom = convertTime(eventTimeFrom);
    var timeTo = convertTime(eventTimeTo);
  
    const selectedOption = select.options[select.selectedIndex].value;
    switch (selectedOption) {
      case "daily":
        if (nbRecursion.value == ""){
          alert("Choisissez un nombre de récursion");
          break;
        }
        addEventNextXDays(activeDay, month, year, timeFrom, timeTo, nbRecursion.value);
        break;
      case "weekly":
        if (nbRecursion.value == ""){
          alert("Choisissez un nombre de récursion");
          break;
        }
        addEventNextXWeeks(activeDay, month, year, timeFrom, timeTo, nbRecursion.value);
        break;
      case "monthly":
        if (nbRecursion.value == ""){
          alert("Choisissez un nombre de récursion");
          break;
        }
        addEventNextXMonths(activeDay, month, year, timeFrom, timeTo, nbRecursion.value);
        break;
      default:
        const newEvent = {
          title: "Disponible",
          time: timeFrom + " - " + timeTo,
        };
        let eventAdded = false;
        if (eventsArr.length > 0) {
          eventsArr.forEach((item) => {
            if (item.day === activeDay && item.month === month + 1 && item.year === year) {
              item.events.push(newEvent);
              eventAdded = true;
            }
          });
        }
      
        if (!eventAdded) {
          eventsArr.push({
            day: activeDay,
            month: month + 1,
            year: year,
            events: [newEvent],
          });
        }
        const stringInfos = activeDay +"/"+ (month+1) +"/"+ year +";"+ newEvent.time + "|";
        stockInfos.value += stringInfos
        addEventWrapper.classList.remove("active");
        addEventFrom.value = "";
        addEventTo.value = "";
        updateEvents(activeDay);
        //select active day and add event class if not added
        const activeDayEl = document.querySelector(".day.active");
        if (!activeDayEl.classList.contains("event")) {
          activeDayEl.classList.add("event");
        }
        break;
    }

    // Envoyer les données au contrôleur via AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "/CalandarController", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Traitez la réponse du contrôleur si nécessaire
            console.log(xhr.responseText);
        }
    };
    var data = JSON.stringify({ "timeFrom": timeFrom, "timeTo": timeTo, "infos": infos });
    xhr.send(data);

    addEventFrom.value = "";
    addEventTo.value = "";
    nbRecursion.value = "";

    initCalendar();
  }

});

function addEventNextXDays(activeDay, month, year, timeFrom, timeTo, recursion) {
  const newEvent = {
    title: "Disponible",
    time: timeFrom + " - " + timeTo,
  };
  // Construction de la chaîne d'informations pour le stockage
  let stringInfos = "";
  let currentDate;
  var newDisponibilite = "";
  for (let i = 0; i < recursion; i++) {
    const eventDate = new Date(year, month, activeDay + i);
    const formattedDate = eventDate.getDate() + "/" + (eventDate.getMonth() + 1) + "/" + eventDate.getFullYear();
    stringInfos = formattedDate + ";" + newEvent.time + "|";
    newDisponibilite += stringInfos;
    addEventWrapper.classList.remove("active");

    currentDate = new Date(today); 
    currentDate.setDate(currentDate.getDate() + i);
    const dayElement = daysContainer[currentDate.getDate() - 1];
    if (dayElement) {
      if (!dayElement.classList.contains("event")) {
        dayElement.classList.add('event');
      }
    }
  }
  stockInfos.value += newDisponibilite;
  setDisponibility(stockInfos.value);
}

function addEventNextXWeeks(activeDay, month, year, timeFrom, timeTo, recursion) {
  const newEvent = {
    title: "Disponible",
    time: timeFrom + " - " + timeTo,
  };
  // Construction de la chaîne d'informations pour le stockage
  let stringInfos = "";
  let currentDate;
  var newDisponibilite = "";
  for (let i = 0; i < (7*recursion); i += 7) {
    const eventDate = new Date(year, month, activeDay + i);
    const formattedDate = eventDate.getDate() + "/" + (eventDate.getMonth() + 1) + "/" + eventDate.getFullYear();
    stringInfos = formattedDate + ";" + newEvent.time + "|";
    newDisponibilite += stringInfos;
    addEventWrapper.classList.remove("active");

    currentDate = new Date(today); 
    currentDate.setDate(currentDate.getDate() + i);
    const dayElement = daysContainer[currentDate.getDate() - 1];
    if (dayElement) {
      if (!dayElement.classList.contains("event")) {
        dayElement.classList.add('event');
      }
    }
  }
  stockInfos.value += newDisponibilite;
  setDisponibility(stockInfos.value);
}

function addEventNextXMonths(activeDay, month, year, timeFrom, timeTo, recursion) {
  const newEvent = {
    title: "Disponible",
    time: timeFrom + " - " + timeTo,
  };
  // Construction de la chaîne d'informations pour le stockage
  let stringInfos = "";
  let currentDate;
  var newDisponibilite = "";
  for (let i = 0; i < recursion; i++) {
    const eventDate = new Date(year, month + i, activeDay);
    const formattedDate = eventDate.getDate() + "/" + (eventDate.getMonth() + 1) + "/" + eventDate.getFullYear();
    stringInfos = formattedDate + ";" + newEvent.time + "|";
    newDisponibilite += stringInfos;
    addEventWrapper.classList.remove("active");

    currentDate = new Date(today); 
    currentDate.setMonth(currentDate.getMonth() + i);
    const dayElement = daysContainer[currentDate.getDate() - 1];
    if (dayElement) {
      if (!dayElement.classList.contains("event")) {
        dayElement.classList.add('event');
      }
    }
  }
  stockInfos.value += newDisponibilite;
  setDisponibility(stockInfos.value);
}

function seChevauchent(time1, time2){
  const [start1, end1] = time1.split(' - ').map(time => time.split(':').map(Number));
  const [start2, end2] = time2.split(' - ').map(time => time.split(':').map(Number));

  if ((start1[0] < end2[0] && start2[0] < end1[0]) || (start1[0] === start2[0] && end1[0] === end2[0])) {
    return true;
  }else {
    return false;
  }
}

function getPlusPetiteHeure(heure1, heure2){
  if(heure1[0] == heure2[0]){ // Même heure
    if(heure1[1] <= heure2[1]) {
      return getDateValide(heure1[0], heure1[1]);
    }else {
      return getDateValide(heure2[0], heure2[1]);
    }
  }else if (heure1[0] < heure2[0]) {
    return getDateValide(heure1[0], heure1[1]);
  }else {
    return getDateValide(heure2[0], heure2[1]);
  }
}

function getPlusGrandeHeure(heure1, heure2){
  if(heure1[0] == heure2[0]){ // Même heure
    if(heure1[1] <= heure2[1]) {
      return getDateValide(heure2[0], heure2[1]);
    }else {
      return getDateValide(heure1[0], heure1[1]);
    }
  }else if (heure1[0] < heure2[0]) {
    return getDateValide(heure2[0], heure2[1]);
  }else {
    return getDateValide(heure1[0], heure1[1]);
  }
}

function dateInvalide(hour1, hour2){
  const [hour1Hour, hour1Minute] = hour1.split(":").map(Number);
  const [hour2Hour, hour2Minute] = hour2.split(":").map(Number);

  if (hour1Hour < hour2Hour) {
      return true;
  } else if (hour1Hour === hour2Hour) {
      return hour1Minute < hour2Minute;
  } else {
      return false;
  }
}

function getDateValide(heure, minute){
  var heureValide = "";
  if(heure < 10){
    heureValide = "0" + heure;
  }else {
    heureValide = heure;
  }

  if(minute < 10) {
    heureValide += ":0" + minute;
  }else {
    heureValide += ":" + minute;
  }
  return heureValide;
}

addEventAllDay.addEventListener("click", () => {
  addEventFrom.value = "00:00";
  addEventTo.value = "23:59";
});


//function to delete event when clicked on event
eventsContainer.addEventListener("click", (e) => {
  if (e.target.classList.contains("event")) {
    if (confirm("Êtes vous sur de vouloir supprimer?")) {
      const eventTitle = e.target.children[0].children[1].innerHTML;
      const eventTimeText = document.querySelector(".event-time");
      eventsArr.forEach((event) => {
        if ( event.day === activeDay && event.month === month + 1 && event.year === year ) {
          event.events.forEach((item, index) => {
            if (item.title === eventTitle) {
              event.events.splice(index, 1);
            }
          });
          //if no events left in a day then remove that day from eventsArr
          if (event.events.length === 0) {
            eventsArr.splice(eventsArr.indexOf(event), 1);
            //remove event class from day
            const activeDayEl = document.querySelector(".day.active");
            if (activeDayEl.classList.contains("event")) {
              var stringInfos = event.day +"/"+ event.month +"/"+ event.year +";"+ eventTimeText.textContent + "|";
              
              stringInfos = stringInfos.trim().replace(/\n\s*/g, "");
              stockInfos.value = stockInfos.value.replace(stringInfos, "");

              activeDayEl.classList.remove("event");
            }
          }
        }
      });
      updateEvents(activeDay);
    }
  }
});

//function to save events in local storage
function saveEvents() {
  const url = window.location.href;
  const segments = url.split('/');
  // Récupérer le dernier segment de l'URL
  const lastSegment = segments[segments.length - 1];

  localStorage.setItem("events"+lastSegment, JSON.stringify(eventsArr));
}

function getEvents() {
  const url = window.location.href;
  const segments = url.split('/');
  // Récupérer le dernier segment de l'URL
  const lastSegment = segments[segments.length - 1];
  //check if events are already saved in local storage then return event else nothing
  if (localStorage.getItem("events"+lastSegment) === null) {
    return;
  }
  eventsArr.push(...JSON.parse(localStorage.getItem("events")));
}

function parseDisponibilite(dispo) {
  
  eventsArr.splice(0, eventsArr.length);
  const parts = dispo.split("|").filter(Boolean);
  
  for (let part of parts) {
    if (part !== "jamais"){
      const [date, time] = part.split(";");
      const [jour, mois, annee] = date.split("/");
      const [start, end] = time.split(" - ");
      
      const newEvent = {
        title: "Disponible",
        time: time,
      };
      
      eventsArr.push({
        day: parseInt(jour),
        month: parseInt(mois),
         year: parseInt(annee),
         events: [newEvent],
       });
    }
  }
}

function setDisponibility(disponibilite) {
  stockInfos.value = disponibilite;
  parseDisponibilite(disponibilite);
  initCalendar();
}

function convertTime(time) {
  //convert time to 24 hour format
  let timeArr = time.split(":");
  let timeHour = timeArr[0];
  let timeMin = timeArr[1];
  time = timeHour + ":" + timeMin ;
  return time;
}

function redirectToMain() {
  window.history.back();
}

function actualiserPage() {
  location.reload();
}

function submitForm() {
  document.getElementById("myForm").submit();
}

function removeAllEvent(){
  setDisponibility("");
}

function changeSelect() {
  if (select.value !== "none") {
    nbRecursion.style.display = "block";
  } else {
    nbRecursion.style.display = "none";
  }
}

