import React, { useLayoutEffect, useEffect, useState } from "react";
import useApiAxios from "./redux/requests/useApiAxios";
import { useTranslation } from "react-i18next";
import { useForm, useController } from "react-hook-form";
import { connect } from "react-redux";
import Swal from "sweetalert2";
import Select from "react-select";
import { setErrors, clearErrors, loading } from "./redux/actions/UserActions";
import { yupResolver } from "@hookform/resolvers/yup";
import * as yup from "yup";

const schema = yup
  .object({
    structure: yup.number().required().positive().integer(),
    status: yup.string().required(),
    fonction: yup.number().required().positive().integer(),
    dateAffectation: yup.date().min("01/01/1960").required(),
    edit: yup.boolean().required(),
  })
  .required();

const statusOptions = ["encours", "mute", "retraite", "remplace"];

const FormStructures = (props) => {
  const structurePosition = {
    ...props.structureP,
    fonction: props.structureP?.fonction?.id || -1,
    structure: props.structureP?.structure?.id || -1,
    dateAffectation: props.structureP?.dateAffectation?.substring(0, 10),
  };
  const [structures, setStructures] = useState([]);
  const [fonctions, setFonctions] = useState([]);
  const privateAxios = useApiAxios();
  const { t } = useTranslation();
  const {
    register,
    control,
    formState: { errors },
    handleSubmit,
  } = useForm({
    defaultValues: structurePosition,
    mode: "onChange",
    resolver: yupResolver(schema),
  });

  const {
    field: {
      value: structureValue,
      onChange: structureOnChange,
      ...restStructureField
    },
  } = useController({ name: "structure", control });

  const {
    field: {
      value: fonctionValue,
      onChange: fonctionOnChange,
      ...restFonctionField
    },
  } = useController({ name: "fonction", control });

  useLayoutEffect(() => {
    privateAxios
      .get(`/structures`)
      .then((res) => {
        if (res.status === 200) {
          let data = res.data;
          data.sort(
            (a, b) =>
              a.typeStructure.name.localeCompare(b.typeStructure.name) ||
              a.subDivision.name.localeCompare(b.subDivision.name) ||
              a.name.localeCompare(b.name)
          );
          setStructures(
            data.map((struct) => ({
              label: `${struct.name} (${struct.typeStructure.name})`,
              value: struct.id,
            }))
          );
        } else {
          Swal.fire(t("error"), t("structure.errorfecth"), "error");
          props.closeModal();
        }
      })
      .catch(function (error) {
        Swal.fire(t("error"), error.message, "error");
        props.closeModal();
      });
  }, []);

  useEffect(() => {
    if (structureValue > 0) {
      privateAxios
        .get(`/structures/${structureValue}/fonctions`)
        .then((res) => {
          if (res.status === 200) {
            let data = res.data;
            data.sort((a, b) => a.fonction.name.localeCompare(b.fonction.name));
            setFonctions(
              data.map((fonction) => ({
                label: `${fonction.fonction.name} (${fonction.nombre})`,
                value: fonction.fonction.id,
              }))
            );
          } else {
            Swal.fire(t("error"), t("structure.errorfecth"), "error");
          }
        })
        .catch(function (error) {
          Swal.fire(t("error"), error.message, "error");
        });
    }
  }, [structureValue]);

  const handleSubmition = (data) => {
    const formData = {
      ...data,
    };
    props.closeModal();
    Swal.fire({
      title: t("save.question"),
      icon: "question",
      showCancelButton: true,
      cancelButtonColor: "#02a1c5",
      confirmButtonText: t("save.btn"),
      confirmButtonColor: "#198754",
    }).then((result) => {
      if (result.isConfirmed) {
        if (data.edit) {
          privateAxios
            .put(`/structuremembre/${data.id}`, formData)
            .then((res) => {
              if (res.status === 200) {
                Swal.fire(t("save.label"), t("save.success"), "success");
                props.setRerender((v) => v + 1);
              } else {
                Swal.fire(t("error"), t("save.error"), "error");
              }
            })
            .catch(function (error) {
              Swal.fire(t("error"), error.message, "error");
            });
        } else {
          privateAxios
            .post(`/structuremembre`, formData)
            .then((res) => {
              if (res.status === 201) {
                Swal.fire(t("save.label"), t("save.success"), "success");
              }
              props.setRerender((v) => v + 1);
            })
            .catch(function (error) {
              Swal.fire(t("error"), error.message, "error");
            });
        }
      }
    });
  };
  const onErrors = (errors) => console.log(errors);

  return (
    <div>
      <form
        className="container-xs m-auto mt-1 p-2"
        method="post"
        id="structure-form"
        onSubmit={handleSubmit(handleSubmition, onErrors)}
      >
        {props.isLoading && (
          <div className="d-flex align-items-lg-center justify-content-center w-100 overlay">
            <div className="spinner-border text-primary" role="status">
              <span className="visually-hidden">Loading...</span>
            </div>
          </div>
        )}
        <h1 className="text-center text-uppercase app-page-title">
          {t("membre_position")}
        </h1>
        <div className="mb-3">
          <label htmlFor="InputStatus" className="form-label">
            {t("structure.status")}
          </label>
          <select
            className="form-control"
            name="status"
            id="InputStatus"
            defaultValue={structurePosition.status}
            aria-invalid={errors?.status ? "true" : "false"}
            {...register("status")}
          >
            <option value="">{t("status.select")}</option>
            {statusOptions.map((option, i) => (
              <option key={`optionS${i}`} value={option}>
                {t(`status.${option.toLowerCase()}`)}
              </option>
            ))}
          </select>
          {errors?.status && (
            <small className="text-danger">{errors?.status.message}</small>
          )}
        </div>
        <div className="mb-3">
          <label htmlFor="InputStructure" className="form-label">
            {t("structure.name")}
          </label>
          <Select
            id="InputStructure"
            className="select-input"
            placeholder={t("structure.select")}
            isClearable
            isSearchable
            options={structures}
            value={
              structureValue
                ? structures.find((x) => x.value == structureValue)
                : structureValue
            }
            onChange={(option) =>
              structureOnChange(option ? option.value : option)
            }
            {...restStructureField}
          />
          {errors?.structure && (
            <small className="text-danger">{errors?.structure.message}</small>
          )}
        </div>
        <div className="mb-3">
          <label htmlFor="InputFonction" className="form-label">
            {t("structure.function")}
          </label>
          <Select
            id="InputFonction"
            className="select-input"
            placeholder={t("structure.select")}
            isClearable
            isSearchable
            options={fonctions}
            value={
              fonctionValue
                ? fonctions.find((x) => x.value == fonctionValue)
                : fonctionValue
            }
            onChange={(option) =>
              fonctionOnChange(option ? option.value : option)
            }
            {...restFonctionField}
          />
          {errors?.fonction && (
            <small className="text-danger">{errors?.fonction.message}</small>
          )}
        </div>
        <div className="mb-3">
          <label htmlFor="InputDateAffectation" className="form-label">
            {t("structure.date_assumption")}
          </label>
          <input
            type="date"
            className="form-control"
            name="dateAffectation"
            id="InputDateAffectation"
            aria-invalid={errors?.dateAffectation ? "true" : "false"}
            {...register("dateAffectation")}
          />
          {errors?.dateConsecration && (
            <small className="text-danger">
              {errors?.dateConsecration.message}
            </small>
          )}
        </div>
        <div className="d-flex justify-content-end gap-2">
          <div>
            <button
              type="button"
              onClick={props.closeModal}
              className="btn btn-secondary"
            >
              Cancel
            </button>
          </div>
          <div>
            <input
              className="btn btn-primary"
              type="submit"
              value={t("submit")}
            />
          </div>
        </div>
      </form>
    </div>
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

export default connect(mapStateToProps, mapActionsToProps)(FormStructures);
