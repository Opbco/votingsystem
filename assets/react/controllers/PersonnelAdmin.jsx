import React, { useEffect } from "react";

import AOS from "aos";
import "aos/dist/aos.css";
import imgUn from "./../../../public/images/staff_un.jpg";
import imgDeux from "./../../../public/images/staff_deux.jpg";
import imgTrois from "./../../../public/images/staff_trois.jpg";
import imgQuatre from "./../../../public/images/staff_quatre.jpg";
import imgCinq from "./../../../public/images/staff_cinq.jpg";
import imgSix from "./../../../public/images/staff_six.jpg";

const staffs = [
  {
    id: 1,
    fullName: "MBA Alphonse",
    fonction: "Directeur—Adjoint",
    titre: "Maître de Conférences",
    image: imgUn,
  },
  {
    id: 2,
    fullName: "EKOMANE Emile",
    fonction:
      "Division des Affaires Académiques, de la Recherche et de la Coopération, Chef",
    titre: "Maître de Conférences",
    image: imgDeux,
  },
  {
    id: 3,
    fullName: "ABOSSOLO Samuel Aimé",
    fonction: "Division de la Scolarite et des Etudes, Chef",
    titre: "Maître de Conférences",
    image: imgTrois,
  },
  {
    id: 4,
    fullName: "MOUSSA 11",
    fonction: "Division de la Formation Continue et à Distance, Chef",
    titre: "Maître de Conférences",
    image: imgQuatre,
  },
  {
    id: 5,
    fullName: "MVONDO, née MBENGON Virginie Byby",
    fonction: "Division Administrative et Financiere, Chef",
    titre: "Conseiller Principal de Jeunesse et d'Animation",
    image: imgCinq,
  },
  {
    id: 6,
    fullName: "ESSENG EBANGA Rameline",
    fonction: "Centre de Documentation, Chef",
    titre: "Cadre Contractuel d'Administration",
    image: imgSix,
  },
];

const PersonnelAdmin = ({ t }) => {
  useEffect(() => {
    AOS.init();
  }, []);

  return (
    <section className="app-home-staff-g">
      <div className="app_section-title">
        <div className="sep-text">
          <div className="sep-text-line"></div>
          <div className="content">
            <h2>{t("our_staff")}</h2>
          </div>
          <div className="sep-text-line"></div>
        </div>
      </div>
      <div className="app-home-staff">
        {staffs.map((staff, index) => (
          <div
            className="home-staff-container"
            style={{ backgroundImage: `url(${staff.image})` }}
            key={`staff${index}`}
            data-aos="flip-right"
          >
            <div className="home-staff-content">
              <h5>{staff.fullName}</h5>
              <em>{staff.titre}</em>
              <h6>{staff.fonction}</h6>
            </div>
          </div>
        ))}
      </div>
    </section>
  );
};

export default PersonnelAdmin;
