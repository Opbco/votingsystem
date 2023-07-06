import React, { useEffect, useState } from "react";
import StepFormWrapper from "./StepFormWrapper";
import { useController } from "react-hook-form";
import publicAxios from "./redux/requests/publicAxios";
import Select from "react-select";

const genderOptions = ["Male", "Female", "Other"];

const ConcoursCandidat = ({ t, register, errors, control }) => {
  const [divisions, setDivisions] = useState([]);

  useEffect(() => {
    publicAxios.get("/open/departments").then((resp) => {
      setDivisions(
        resp.data.map((item, i) => ({
          label: `${item.name} - ${item.state.name}`,
          value: item.id,
        }))
      );
    });
  }, []);

  const {
    field: {
      value: divisionValue,
      onChange: divisionOnChange,
      ...restDivisionField
    },
  } = useController({ name: "divisionOrigin", control });

  return (
    <StepFormWrapper title={t("infor_personnelle")}>
      <div className="mb-3">
        <label htmlFor="InputGender" className="form-label">
          {t("gender.label")}
        </label>
        <select
          className="form-control"
          name="gender"
          id="InputGender"
          aria-invalid={errors?.gender ? "true" : "false"}
          {...register("gender")}
        >
          <option value="">{t("gender.select")}</option>
          {genderOptions.map((option, i) => (
            <option key={`optionG${i}`} value={option}>
              {t(`gender.${option.toLowerCase()}`)}
            </option>
          ))}
        </select>
        {errors?.gender && (
          <small className="text-danger">{errors?.gender.message}</small>
        )}
      </div>
      <div className="row g-2 mb-3">
        <div className="col-md">
          <label htmlFor="InputFirstName" className="form-label">
            {t("firstname")}
          </label>
          <input
            type="text"
            className="form-control"
            name="firstName"
            id="InputFirstName"
            aria-invalid={errors?.firstName ? "true" : "false"}
            {...register("firstName")}
          />
          {errors?.firstName && (
            <small className="text-danger">{errors?.firstName.message}</small>
          )}
        </div>
        <div className="col-md">
          <label htmlFor="InputLastName" className="form-label">
            {t("lastName")}
          </label>
          <input
            type="text"
            className="form-control"
            name="lastName"
            id="InputLastName"
            aria-invalid={errors?.lastName ? "true" : "false"}
            {...register("lastName")}
          />
          {errors?.lastName && (
            <small className="text-danger">{errors?.lastName.message}</small>
          )}
        </div>
      </div>
      <div className="row g-2 mb-3">
        <div className="col-md">
          <label htmlFor="InputDob" className="form-label">
            {t("dob", { ns: "login" })}
          </label>
          <input
            type="date"
            className="form-control"
            name="dob"
            id="InputDob"
            aria-invalid={errors?.dob ? "true" : "false"}
            {...register("dob")}
          />
          {errors?.dob && (
            <small className="text-danger">{errors?.dob.message}</small>
          )}
        </div>
        <div className="col-md">
          <label htmlFor="InputPob" className="form-label">
            {t("pob", { ns: "login" })}
          </label>
          <input
            type="text"
            className="form-control"
            name="pob"
            id="InputPob"
            aria-invalid={errors?.pob ? "true" : "false"}
            {...register("pob")}
          />
          {errors?.pob && (
            <small className="text-danger">{errors?.pob.message}</small>
          )}
        </div>
      </div>
      <div className="row g-2 mb-3">
        <div className="col-md">
          <label htmlFor="InputNationality" className="form-label">
            {t("nationality")}
          </label>
          <input
            type="text"
            className="form-control"
            name="nationality"
            id="InputNationality"
            aria-invalid={errors?.nationality ? "true" : "false"}
            {...register("nationality")}
          />
          {errors?.nationality && (
            <small className="text-danger">{errors?.nationality.message}</small>
          )}
        </div>
        <div className="col-md">
          <label htmlFor="InputDivision" className="form-label">
            {t("concours.divisionOrigin")}
          </label>
          <Select
            className="select-input"
            placeholder={t("gender.select")}
            isClearable
            isSearchable
            options={divisions}
            value={
              divisionValue
                ? divisions.find((x) => x.value == divisionValue)
                : divisionValue
            }
            onChange={(option) =>
              divisionOnChange(option ? option.value : option)
            }
            {...restDivisionField}
          />
          {errors?.divisionOrigin && (
            <small className="text-danger">
              {errors?.divisionOrigin.message}
            </small>
          )}
        </div>
      </div>
      <div className="row g-2 mb-3">
        <div className="col-md">
          <label htmlFor="InputPhone" className="form-label">
            {t("phone")}
          </label>
          <input
            type="text"
            className="form-control"
            name="phone"
            id="InputPhone"
            aria-invalid={errors?.phone ? "true" : "false"}
            {...register("phone")}
          />
          {errors?.phone && (
            <small className="text-danger">{errors?.phone.message}</small>
          )}
        </div>
        <div className="col-md">
          <label htmlFor="InputWhatsapp" className="form-label">
            {t("whatsapp")}
          </label>
          <input
            type="text"
            className="form-control"
            name="whatsapp"
            id="InputWhatsapp"
            aria-invalid={errors?.whatsapp ? "true" : "false"}
            {...register("whatsapp")}
          />
          {errors?.whatsapp && (
            <small className="text-danger">{errors?.whatsapp.message}</small>
          )}
        </div>
      </div>
      <div className="row g-2 mb-3">
        <div className="col-md">
          <label htmlFor="InputEmail" className="form-label">
            {t("email")}
          </label>
          <input
            type="email"
            className="form-control"
            name="email"
            id="InputEmail"
            aria-invalid={errors?.email ? "true" : "false"}
            {...register("email")}
          />
          {errors?.email && (
            <small className="text-danger">{errors?.email.message}</small>
          )}
        </div>
        <div className="col-md">
          <label htmlFor="Inputbp" className="form-label">
            {t("bp")}
          </label>
          <input
            type="text"
            className="form-control"
            name="bp"
            id="Inputbp"
            aria-invalid={errors?.bp ? "true" : "false"}
            {...register("bp")}
          />
          {errors?.bp && (
            <small className="text-danger">{errors?.bp.message}</small>
          )}
        </div>
      </div>
      <div className="row g-2 mb-3">
        <div className="col-md">
          <label htmlFor="InputFatherName" className="form-label">
            {t("family.fatherName")}
          </label>
          <input
            type="text"
            className="form-control"
            name="fatherName"
            id="InputFatherName"
            aria-invalid={errors?.fatherName ? "true" : "false"}
            {...register("fatherName")}
          />
          {errors?.fatherName && (
            <small className="text-danger">{errors?.fatherName.message}</small>
          )}
        </div>
        <div className="col-md">
          <label htmlFor="InputMotherName" className="form-label">
            {t("family.motherName")}
          </label>
          <input
            type="text"
            className="form-control"
            name="motherName"
            id="InputMotherName"
            aria-invalid={errors?.motherName ? "true" : "false"}
            {...register("motherName")}
          />
          {errors?.motherName && (
            <small className="text-danger">{errors?.motherName.message}</small>
          )}
        </div>
      </div>
      <div className="mb-3">
        <label htmlFor="InputParentsPhones" className="form-label">
          {t("concours.parentsPhones")}
        </label>
        <input
          type="text"
          className="form-control"
          name="parentsPhones"
          id="InputParentsPhones"
          aria-invalid={errors?.parentsPhones ? "true" : "false"}
          {...register("parentsPhones")}
        />
        {errors?.parentsPhones && (
          <small className="text-danger">{errors?.parentsPhones.message}</small>
        )}
      </div>
    </StepFormWrapper>
  );
};

export default ConcoursCandidat;
