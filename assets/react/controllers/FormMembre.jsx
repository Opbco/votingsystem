import React, { useEffect, useState } from "react";
import useApiAxios from "./redux/requests/useApiAxios";
import AvatarUploader from "./AvatarUploader";
import { useTranslation } from "react-i18next";
import { useForm, useController } from "react-hook-form";
import { connect } from "react-redux";
import Swal from "sweetalert2";
import { setErrors, clearErrors, loading } from "./redux/actions/UserActions";
import { yupResolver } from "@hookform/resolvers/yup";
import * as yup from "yup";
import { Link } from "react-router-dom";
import LocationInput from "./LocationInput";
import Select from "react-select";

const schema = yup
  .object({
    name: yup.string().required(),
    dob: yup.date().min("01/01/1920").required(),
    pob: yup.string().required(),
    maritalStatus: yup.string().required("Your marital status is required"),
    title: yup.string().required("Your Title is required"),
    description: yup
      .string()
      .min(100, "At least 100 caracters are required")
      .required("A description of you is required"),
    gender: yup.string().required("Your gender is required"),
    conjointName: yup.string().when("maritalStatus", {
      is: "Maried",
      then: (schema) => schema.required("Spouse's name is required"),
      otherwise: (schema) => schema.notRequired(),
    }),
    conjointFonction: yup.string().when("maritalStatus", {
      is: "Maried",
      then: (schema) => schema.required("Spouse's function is required"),
      otherwise: (schema) => schema.notRequired(),
    }),
    conjointContact: yup.string().when("maritalStatus", {
      is: "Maried",
      then: (schema) => schema.required("Spouse's contacts is required"),
      otherwise: (schema) => schema.notRequired(),
    }),
    conjointAdress: yup.string().when("maritalStatus", {
      is: "Maried",
      then: (schema) => schema.required("Spouse's address is required"),
      otherwise: (schema) => schema.notRequired(),
    }),
    nbreEnfant: yup.number().integer(),
    ethnie: yup.string().required(),
    fatherName: yup.string().required(),
    motherName: yup.string().required(),
    phone: yup
      .string()
      .matches(/^[(]?[0-9]{3}[)]?[0-9]{7}[\d{1}]?[\d{1}]?$/, {
        message: "Invalid phone number",
      })
      .required(),
    handicap: yup
      .string()
      .required("handicap is required, put RAS if no handicap"),
    whatsapp: yup
      .string()
      .matches(/^[(]?[0-9]{3}[)]?[0-9]{7}[\d{1}]?[\d{1}]?$/, {
        message: "Invalid phone number",
      })
      .required(),
    dateConsecration: yup.date().min("01/01/1960").required(),
    paroisseConsecration: yup
      .number()
      .positive()
      .integer()
      .required("Church of consecration is required"),
    profession: yup.string().required(),
  })
  .required();

const maritalOptions = ["Maried", "Divorced", "Single", "Widow"];
const genderOptions = ["Male", "Female", "Other"];

const FormMembre = (props) => {
  const membre = props.membre;
  const [address, setAddress] = useState({
    address: membre.address,
    longitude: membre.longitude,
    latitude: membre.latitude,
  });
  const [avatar, setAvatar] = useState({
    ...membre.avatar,
  });
  const privateAxios = useApiAxios();
  const { t } = useTranslation();

  const {
    register,
    control,
    formState: { errors },
    handleSubmit,
    reset,
  } = useForm({
    defaultValues: membre,
    mode: "onChange",
    resolver: yupResolver(schema),
  });

  useEffect(() => {
    reset({
      ...membre,
      paroisseConsecration: membre.paroisseConsecration?.id,
      dateConsecration: membre.dateConsecration?.substring(0, 10),
      dob: membre.dob?.substring(0, 10),
    });
    setAvatar({ ...membre.avatar });
    setAddress({
      address: membre.address,
      longitude: membre.longitude,
      latitude: membre.latitude,
    });
  }, [reset, membre]);

  const paroisses = React.useMemo(() => {
    return props.paroisses.map((item, i) => ({
      label: item.name,
      value: item.id,
    }));
  }, [props.paroisses]);

  const {
    field: {
      value: paroisseValue,
      onChange: paroisseOnChange,
      ...restParoisseField
    },
  } = useController({ name: "paroisseConsecration", control });

  /*console.log(avatar);
  console.log(paroisses); */

  const handleSubmition = (data) => {
    const formData = {
      ...data,
      ...address,
      avatar: avatar.id,
    };
    props.loading();
    Swal.fire({
      title: t("save.question"),
      icon: "question",
      showCancelButton: true,
      cancelButtonColor: "#02a1c5",
      confirmButtonText: t("save.btn"),
      confirmButtonColor: "#198754",
    }).then((result) => {
      if (result.isConfirmed) {
        privateAxios
          .put(`/personnels/${data.id}`, formData)
          .then((res) => {
            if (res.status === 200) {
              Swal.fire(t("save.label"), t("save.success"), "success");
              props.clearErrors();
            } else {
              Swal.fire(t("error"), t("save.error"), "error");
            }
          })
          .catch(function (error) {
            Swal.fire(t("error"), error.message, "error");
          });
      } else {
        props.clearErrors();
      }
    });
  };
  const onErrors = (errors) => console.log(errors);

  return (
    <form
      className="container-xs m-auto mt-1 p-2"
      method="post"
      id="membre-form"
      onSubmit={handleSubmit(handleSubmition, onErrors)}
      style={{ position: "relative" }}
    >
      {props.isLoading && (
        <div className="d-flex align-items-lg-center justify-content-center w-100 overlay">
          <div className="spinner-border text-primary" role="status">
            <span className="visually-hidden">Loading...</span>
          </div>
        </div>
      )}
      <h1 className="text-center text-uppercase app-page-title">
        {t("membre_profile")}
      </h1>
      <section className="member-form mt-3">
        <div className="membre-main">
          {props.error && (
            <div
              className="alert alert-warning alert-dismissible fade show"
              role="alert"
            >
              <strong>Error!</strong> {props.error}.
              <button
                type="button"
                className="btn-close"
                data-bs-dismiss="alert"
                aria-label="Close"
                onClick={() => {
                  props.clearErrors();
                }}
              ></button>
            </div>
          )}
          <fieldset>
            <legend>{t("maritalStatus.label")}</legend>
            <div className="mb-3">
              <label htmlFor="InputMaritalStatus" className="form-label">
                {t("maritalStatus.status")}
              </label>
              <select
                className="form-control"
                name="maritalStatus"
                id="InputMaritalStatus"
                aria-invalid={errors?.maritalStatus ? "true" : "false"}
                {...register("maritalStatus")}
              >
                <option value="">{t("maritalStatus.select")}</option>
                {maritalOptions.map((option, i) => (
                  <option
                    key={`optionM${i}`}
                    value={option}
                    {...{
                      selected:
                        option == membre.maritalStatus ? "selected" : null,
                    }}
                  >
                    {t(`maritalStatus.${option.toLowerCase()}`)}
                  </option>
                ))}
              </select>
              {errors?.maritalStatus && (
                <small className="text-danger">
                  {errors?.maritalStatus.message}
                </small>
              )}
            </div>
            <div className="row g-2 mb-3">
              <div className="col-md">
                <label htmlFor="InputConjointName" className="form-label">
                  {t("conjoint.name")}
                </label>
                <input
                  type="text"
                  className="form-control"
                  name="conjointName"
                  id="InputConjointName"
                  aria-invalid={errors?.conjointName ? "true" : "false"}
                  {...register("conjointName")}
                />
                {errors?.conjointName && (
                  <small className="text-danger">
                    {errors?.conjointName.message}
                  </small>
                )}
              </div>
              <div className="col-md">
                <label htmlFor="InputConjointFonction" className="form-label">
                  {t("conjoint.fonction")}
                </label>
                <input
                  type="text"
                  className="form-control"
                  name="conjointFonction"
                  id="InputConjointFonction"
                  aria-invalid={errors?.conjointFonction ? "true" : "false"}
                  {...register("conjointFonction")}
                />
                {errors?.conjointFonction && (
                  <small className="text-danger">
                    {errors?.conjointFonction.message}
                  </small>
                )}
              </div>
            </div>
            <div className="row g-2 mb-3">
              <div className="col-md">
                <label htmlFor="InputConjointContact" className="form-label">
                  {t("conjoint.contact")}
                </label>
                <input
                  type="text"
                  className="form-control"
                  name="conjointContact"
                  id="InputConjointContact"
                  aria-invalid={errors?.conjointContact ? "true" : "false"}
                  {...register("conjointContact")}
                />
                {errors?.conjointContact && (
                  <small className="text-danger">
                    {errors?.conjointContact.message}
                  </small>
                )}
              </div>
              <div className="col-md">
                <label htmlFor="InputConjointAdress" className="form-label">
                  {t("conjoint.adress")}
                </label>
                <input
                  type="text"
                  className="form-control"
                  name="conjointAdress"
                  id="InputConjointAdress"
                  aria-invalid={errors?.conjointAdress ? "true" : "false"}
                  {...register("conjointAdress")}
                />
                {errors?.conjointAdress && (
                  <small className="text-danger">
                    {errors?.conjointAdress.message}
                  </small>
                )}
              </div>
            </div>
            <div className="mb-3">
              <label htmlFor="InputNbreEnfant" className="form-label">
                {t("conjoint.nbreEnfant")}
              </label>
              <input
                type="number"
                className="form-control"
                name="nbreEnfant"
                id="InputNbreEnfant"
                aria-invalid={errors?.nbreEnfant ? "true" : "false"}
                {...register("nbreEnfant")}
              />
              {errors?.nbreEnfant && (
                <small className="text-danger">
                  {errors?.nbreEnfant.message}
                </small>
              )}
            </div>
          </fieldset>
          <fieldset className="mt-3">
            <legend>{t("family.label")}</legend>
            <div className="row g-2 mb-3">
              <div className="col-md">
                <label htmlFor="InputEthnie" className="form-label">
                  {t("family.ethnie")}
                </label>
                <input
                  type="text"
                  className="form-control"
                  name="ethnie"
                  id="InputEthnie"
                  aria-invalid={errors?.ethnie ? "true" : "false"}
                  {...register("ethnie")}
                />
                {errors?.ethnie && (
                  <small className="text-danger">
                    {errors?.ethnie.message}
                  </small>
                )}
              </div>
              <div className="col-md">
                <label htmlFor="InputHandicap" className="form-label">
                  {t("handicap")}
                </label>
                <input
                  type="text"
                  className="form-control"
                  name="handicap"
                  id="InputHandicap"
                  aria-invalid={errors?.handicap ? "true" : "false"}
                  {...register("handicap")}
                />
                {errors?.handicap && (
                  <small className="text-danger">
                    {errors?.handicap.message}
                  </small>
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
                  <small className="text-danger">
                    {errors?.fatherName.message}
                  </small>
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
                  <small className="text-danger">
                    {errors?.motherName.message}
                  </small>
                )}
              </div>
            </div>
          </fieldset>
          <fieldset className="mt-3">
            <legend>{t("consecration.label")}</legend>
            <div className="row g-2 mb-3">
              <div className="col-md">
                <label
                  htmlFor="InputParoisseConsecration"
                  className="form-label"
                >
                  {t("consecration.paroisse")}
                </label>
                <Select
                  className="select-input"
                  placeholder={t("gender.select")}
                  isClearable
                  isSearchable
                  options={paroisses}
                  value={
                    paroisseValue
                      ? paroisses.find((x) => x.value == paroisseValue)
                      : paroisseValue
                  }
                  onChange={(option) =>
                    paroisseOnChange(option ? option.value : option)
                  }
                  {...restParoisseField}
                />
                {errors?.paroisseConsecration && (
                  <small className="text-danger">
                    {errors?.paroisseConsecration.message}
                  </small>
                )}
              </div>
              <div className="col-md">
                <label htmlFor="InputDateConsecration" className="form-label">
                  {t("consecration.date")}
                </label>
                <input
                  type="date"
                  className="form-control"
                  name="dateConsecration"
                  id="InputDateConsecration"
                  aria-invalid={errors?.dateConsecration ? "true" : "false"}
                  {...register("dateConsecration")}
                />
                {errors?.dateConsecration && (
                  <small className="text-danger">
                    {errors?.dateConsecration.message}
                  </small>
                )}
              </div>
            </div>
          </fieldset>
          <fieldset className="mt-3">
            <legend>{t("description.group")}</legend>
            <div className="row mb-3">
              <div className="col">
                <label htmlFor="InputDescription" className="form-label">
                  {t("description.label")}
                </label>
                <textarea
                  className="form-control"
                  name="description"
                  id="InputDescription"
                  aria-invalid={errors?.description ? "true" : "false"}
                  {...register("description")}
                  rows={4}
                ></textarea>
                {errors?.description && (
                  <small className="text-danger">
                    {errors?.description.message}
                  </small>
                )}
              </div>
            </div>
          </fieldset>
          <div className="d-flex justify-content-between mt-4">
            <div>
              <input
                className="btn btn-primary"
                type="submit"
                value={t("submit")}
              />
            </div>
            <div>
              <Link to={"/membre/print"} className="btn btn-outline-success">
                {t("print")}
              </Link>
            </div>
          </div>
        </div>
        <aside className="membre-picture">
          <div className="avatar">
            <AvatarUploader
              name={membre.avatar ? `${membre.avatar.id}` : `${membre.id}`}
              setAvatar={setAvatar}
              src={
                Boolean(membre.avatar?.fileName)
                  ? `https://127.0.0.1:8000/uploads/${membre.avatar.fileName}`
                  : "https://127.0.0.1:8000/images/avatar.webp"
              }
            />
          </div>
          <div className="mb-3">
            <label htmlFor="InputTitle" className="form-label">
              {t("title")}
            </label>
            <input
              type="text"
              className="form-control"
              name="title"
              id="InputTitle"
              aria-invalid={errors?.title ? "true" : "false"}
              {...register("title")}
            />
            {errors?.title && (
              <small className="text-danger">{errors?.title.message}</small>
            )}
          </div>
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
                <option
                  key={`optionG${i}`}
                  value={option}
                  {...{
                    selected: option == membre.gender ? "selected" : null,
                  }}
                >
                  {t(`gender.${option.toLowerCase()}`)}
                </option>
              ))}
            </select>
            {errors?.gender && (
              <small className="text-danger">{errors?.gender.message}</small>
            )}
          </div>
          <div className="mb-3">
            <label htmlFor="InputName" className="form-label">
              {t("name", { ns: "login" })}
            </label>
            <input
              type="text"
              className="form-control"
              name="name"
              id="InputName"
              aria-invalid={errors?.name ? "true" : "false"}
              {...register("name")}
            />
            {errors?.name && (
              <small className="text-danger">{errors?.name.message}</small>
            )}
          </div>
          <div className="mb-3">
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
          <div className="mb-3">
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
          <div className="mb-3">
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
          <div className="mb-3">
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
          <div className="mb-3">
            <label htmlFor="InputProfession" className="form-label">
              {t("profession")}
            </label>
            <input
              type="text"
              className="form-control"
              name="profession"
              id="InputProfession"
              aria-invalid={errors?.profession ? "true" : "false"}
              {...register("profession")}
            />
            {errors?.profession && (
              <small className="text-danger">
                {errors?.profession.message}
              </small>
            )}
          </div>
          <LocationInput
            address={membre.address ? membre.address : "Yaounde"}
            setAddress={setAddress}
          />
        </aside>
      </section>
    </form>
  );
};

const mapStateToProps = (state) => ({
  error: state.errors,
  isLoading: state.loading,
});

const mapActionsToProps = {
  setErrors,
  loading,
  clearErrors,
};

export default connect(mapStateToProps, mapActionsToProps)(FormMembre);
