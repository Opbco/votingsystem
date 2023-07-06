import React, { useState, useEffect } from "react";
import useApiAxios from "./../redux/requests/useApiAxios";
import { useTranslation } from "react-i18next";
import { connect } from "react-redux";
import { setErrors, clearErrors, setMe } from "./../redux/actions/UserActions";
import FormMembre from "../FormMembre";
import StructureMembre from "../StructureMembre";

const Membre = (props) => {
  const [membre, setMembre] = useState({});
  const [paroisses, setParoisses] = useState([]);
  const privateAxios = useApiAxios();
  const { t } = useTranslation();

  useEffect(() => {
    (async () => {
      const response = await privateAxios.get(`/personnels/me`);
      if (response.status === 200) {
        setMembre(response.data);
        props.setMe(response.data);
      }
    })();
    (async () => {
      const response = await privateAxios.get(`/structures/paroisses`);
      if (response.status === 200) {
        setParoisses(response.data);
      }
    })();
  }, []);

  return (
    <>
      <nav>
        <div className="nav nav-tabs" id="nav-tab" role="tablist">
          <button
            className="nav-link active"
            id="nav-home-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-home"
            type="button"
            role="tab"
            aria-controls="nav-home"
            aria-selected="true"
          >
            {t("membre_personnal")}
          </button>
          <button
            className="nav-link"
            id="nav-profile-tab"
            data-bs-toggle="tab"
            data-bs-target="#nav-profile"
            type="button"
            role="tab"
            aria-controls="nav-profile"
            aria-selected="false"
          >
            {t("membre_professional")}
          </button>
        </div>
      </nav>
      <div className="tab-content" id="nav-tabContent">
        <div
          className="tab-pane fade show active"
          id="nav-home"
          role="tabpanel"
          aria-labelledby="nav-home-tab"
          tabIndex="0"
        >
          <FormMembre
            membre={membre}
            setMembre={setMembre}
            paroisses={paroisses}
          />
        </div>
        <div
          className="tab-pane fade"
          id="nav-profile"
          role="tabpanel"
          aria-labelledby="nav-profile-tab"
          tabIndex="0"
        >
          <StructureMembre membre={membre} setMembre={setMembre} />
        </div>
      </div>
    </>
  );
};

const mapStateToProps = (state) => ({
  error: state.errors,
  isLoading: state.loading,
});

const mapActionsToProps = {
  setErrors,
  clearErrors,
  setMe,
};

export default connect(mapStateToProps, mapActionsToProps)(Membre);
