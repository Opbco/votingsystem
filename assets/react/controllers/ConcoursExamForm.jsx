import React, { useEffect, useState } from "react";
import StepFormWrapper from "./StepFormWrapper";
import { useController } from "react-hook-form";
import publicAxios from "./redux/requests/publicAxios";
import Select from "react-select";

const levelOptions = ["premiere", "deuxieme", "troisieme"];
const languageOptions = ["french", "english"];

const ConcoursExamForm = ({ t, register, errors, control }) => {
  const [specialities, setSpecialities] = useState([]);
  const [centres, setCentres] = useState([]);

  useEffect(() => {
    publicAxios.get("/open/examcenters").then((resp) => {
      setCentres(
        resp.data.map((item, i) => ({
          label: `${item.name} - ${item.division.name}`,
          value: item.id,
        }))
      );
    });
  }, []);

  useEffect(() => {
    publicAxios.get("/open/specialities").then((resp) => {
      setSpecialities(
        resp.data.map((item, i) => ({
          label: item.name,
          value: item.id,
        }))
      );
    });
  }, []);

  const {
    field: {
      value: specialityValue,
      onChange: specialityOnChange,
      ...restSpecialityField
    },
  } = useController({ name: "speciality", control });

  const {
    field: {
      value: centresValue,
      onChange: centresOnChange,
      ...restCentresField
    },
  } = useController({ name: "examCenter", control });

  return (
    <StepFormWrapper title={t("infor_exam_session")}>
      <div className="mb-3">
        <label htmlFor="InputLevel" className="form-label">
          {t("concours.level.title")}
        </label>
        <select
          className="form-control"
          name="level"
          id="InputLevel"
          aria-invalid={errors?.level ? "true" : "false"}
          {...register("level")}
        >
          <option value="">{t("concours.level.select")}</option>
          {levelOptions.map((option, i) => (
            <option key={`optionM${i}`} value={option}>
              {t(`concours.level.${option.toLowerCase()}`)}
            </option>
          ))}
        </select>
        {errors?.level && (
          <small className="text-danger">{errors?.level.message}</small>
        )}
      </div>
      <div className="mb-3">
        <label htmlFor="InputLangue" className="form-label">
          {t("concours.langue.title")}
        </label>
        <select
          className="form-control"
          name="language"
          id="InputLangue"
          aria-invalid={errors?.language ? "true" : "false"}
          {...register("language")}
        >
          <option value="">{t("concours.langue.select")}</option>
          {languageOptions.map((option, i) => (
            <option key={`optionM${i}`} value={option}>
              {t(`concours.langue.${option.toLowerCase()}`)}
            </option>
          ))}
        </select>
        {errors?.language && (
          <small className="text-danger">{errors?.language.message}</small>
        )}
      </div>
      <div className="mb-3">
        <label htmlFor="InputSpeciality" className="form-label">
          {t("concours.speciality")}
        </label>
        <Select
          className="select-input"
          placeholder={t("gender.select")}
          isClearable
          isSearchable
          options={specialities}
          value={
            specialityValue
              ? specialities.find((x) => x.value == specialityValue)
              : specialityValue
          }
          onChange={(option) =>
            specialityOnChange(option ? option.value : option)
          }
          {...restSpecialityField}
        />
        {errors?.speciality && (
          <small className="text-danger">{errors?.speciality.message}</small>
        )}
      </div>
      <div className="mb-3">
        <label htmlFor="InputExamCenter" className="form-label">
          {t("concours.centres")}
        </label>
        <Select
          className="select-input"
          placeholder={t("gender.select")}
          isClearable
          isSearchable
          options={centres}
          value={
            centresValue
              ? centres.find((x) => x.value == centresValue)
              : centresValue
          }
          onChange={(option) => centresOnChange(option ? option.value : option)}
          {...restCentresField}
        />
        {errors?.examCenter && (
          <small className="text-danger">{errors?.examCenter.message}</small>
        )}
      </div>
      <div className="col-md">
        <label htmlFor="InputNbAttempt" className="form-label">
          {t("concours.nbAttempt")}
        </label>
        <input
          type="text"
          className="form-control"
          name="nbAttempt"
          id="InputNbAttempt"
          aria-invalid={errors?.nbAttempt ? "true" : "false"}
          {...register("nbAttempt")}
        />
        {errors?.nbAttempt && (
          <small className="text-danger">{errors?.nbAttempt.message}</small>
        )}
      </div>
    </StepFormWrapper>
  );
};

export default ConcoursExamForm;
