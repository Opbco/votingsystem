import React, { useEffect } from "react";

function pad(n) {
  return n < 10 ? "0" + n : n;
}

var monthNames = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

const EventDate = ({ day, month }) => {
  return (
    <div className="date">
      <div className="day">{day}</div>
      <div className="month">{monthNames[month]}</div>
    </div>
  );
};

const Event = ({ date_time, structure, title, t }) => {
  const datetime = new Date(date_time);
  const day = pad(datetime.getDate());
  const month = datetime.getMonth();
  const time = `${pad(datetime.getHours())}:${pad(datetime.getMinutes())}`;
  return (
    <div className="app-home-event">
      <div className="event-wrapper">
        <EventDate day={day} month={month} />
        <h3 className="title">{title}</h3>
        <div className="when-where">
          {time}
          <br />
          {structure}
        </div>
        <a href="#" title={title} className="button-readmore">
          <span className="btext">{t("read_more")}</span>
        </a>
      </div>
    </div>
  );
};

const events = [
  {
    title: "Rentree scolaire 2023/2024 des enseignants et staff administratif",
    date_time: "August 27, 2023 08:00:00",
    structure: "Campus de l'ISABEE",
  },
  {
    title:
      "Date limite du depot des dossiers de candidature aux concours d'entree a l'ISABEE",
    date_time: "August 02, 2023 19:00:00",
    structure: "Les differents centre d'examen",
  },
];

const Programmations = ({ t }) => {
  return (
    <div className="app_home_progammation">
      <div className="app_programmation_bg"></div>
      <div className="app_home_programmation-content">
        <div className="app_section-title">
          <div className="sep-text">
            <div className="sep-text-line"></div>
            <div className="content">
              <h2>{t("upcoming_events")}</h2>
            </div>
            <div className="sep-text-line"></div>
          </div>
        </div>
        <div className="app_home_programmation_list">
          {events.map((e, index) => (
            <Event
              key={`event${index}`}
              date_time={e.date_time}
              structure={e.structure}
              title={e.title}
              t={t}
            />
          ))}
        </div>
      </div>
    </div>
  );
};

export default Programmations;
