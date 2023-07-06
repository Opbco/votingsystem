import React, { useEffect } from "react";
import CONSTANTS from "../utils/Constants";
import { useTranslation } from "react-i18next";
import { BsHeadphones, BsFilePdf, BsYoutube } from "react-icons/bs";
import AOS from "aos";
import "aos/dist/aos.css";
import { format } from "date-fns";

const SermonButton = (props) => (
  <div className="sermon-btn">{props.children}</div>
);

const Sermon = ({ sermon }) => {
  const { t } = useTranslation();

  useEffect(() => {
    AOS.init();
  }, []);

  return (
    <div className="app_sermon" data-aos="fade-right">
      <div className="app_sermon_content">
        <div className="sermon_image">
          <img
            src={`${CONSTANTS.BASE_URL}/uploads/${sermon.membre.avatar?.fileName}`}
            alt={sermon.membre.name}
            style={{
              maxWidth: "100%",
              objectPosition: "center center",
              objectFit: "cover",
              width: "110px",
              aspectRatio: 1,
            }}
          />
        </div>
        <div className="sermon_details">
          <div className="title">{sermon.title}</div>
          <div className="subtitle">
            {t("sermon.message_from_category", {
              from: `${sermon.membre.title} ${sermon.membre.name}`,
              when: format(new Date(sermon.date), "dd MMM yyyy"),
              category: sermon.category,
            })}
          </div>
        </div>
      </div>
      <div className="app_sermon_docs">
        <SermonButton>
          <BsHeadphones className="sermon-icon" />
        </SermonButton>
        <SermonButton>
          <BsYoutube className="sermon-icon" />
        </SermonButton>
        <SermonButton>
          <BsFilePdf className="sermon-icon" />
        </SermonButton>
      </div>
    </div>
  );
};

const sermonsList = [
  {
    title: "Attitudes d'évangélisation efficace",
    date: "July 20, 2022 15:00:00",
    category: "Adoration",
    details: "Bien evangeliser requier beaucoup de ...",
    membre: {
      name: "Owono Philippe brice",
      title: "Rev Pasteur",
      avatar: { fileName: "me.jpg" },
    },
  },
  {
    title: "Toutes les choses sont à vous",
    date: "June 20, 2021 15:00:00",
    category: "Salut",
    details: "Toutes choses appartiennent au Seigneur ...",
    membre: {
      name: "Bemdjon Tonye",
      title: "Rev Pasteur",
      avatar: { fileName: "pasto.jpg" },
    },
  },
  {
    title: "Un appel aux amis charismatiques",
    date: "April 16, 2023 15:00:00",
    category: "Adoration",
    details: "Charismatiques doivent etres vos amis ...",
    membre: {
      name: "Bemdjon Tonye",
      title: "Rev Pasteur",
      avatar: { fileName: "pasto.jpg" },
    },
  },
];

const Sermons = ({ sermon }) => {
  const { t } = useTranslation();

  useEffect(() => {
    AOS.init();
  }, []);

  return (
    <section className="app_sermons" data-aos="zoom-out-right">
      <div className="app_section-title">
        <div className="sep-text">
          <div className="sep-text-line"></div>
          <div className="content">
            <h2>{t("sermon.title")}</h2>
          </div>
          <div className="sep-text-line"></div>
        </div>
      </div>
      <div className="app_sermons_list">
        {sermonsList.map((sermon, index) => (
          <Sermon key={`sermon${index}`} sermon={sermon} />
        ))}
      </div>
    </section>
  );
};

export default Sermons;
