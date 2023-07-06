import React from "react";
import Programmations from "../Programmations";
import PersonnelAdmin from "../PersonnelAdmin";
import { useTranslation } from "react-i18next";

const Home = () => {
  const { t } = useTranslation();
  return (
    <div>
      <h2 className="text-center text-uppercase app-page-title">{t("home")}</h2>
      <Programmations t={t} />
      <PersonnelAdmin t={t} />
    </div>
  );
};

export default Home;
