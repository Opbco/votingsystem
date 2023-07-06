import React, { useState } from "react";
import { Form } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { useForm } from "react-hook-form";
import { connect } from "react-redux";
import { useNavigate } from "react-router-dom";
import { registerUser, clearErrors } from "./../redux/actions/UserActions";
import { yupResolver } from "@hookform/resolvers/yup";
import * as yup from "yup";
import LocationInput from "../LocationInput";

const schema = yup
  .object({
    name: yup.string().required(),
    dob: yup.date().min("01/01/1920").required(),
    pob: yup.string().required(),
    username: yup.string().min(6).required(),
    email: yup.string().email().required(),
    confirm_email: yup
      .string()
      .oneOf([yup.ref("email"), null], "Does not match with email!")
      .email()
      .required("Required"),
    plainPassword: yup
      .string()
      .min(4, "Password should be at least 4 charaters")
      .required("Password is required"),
    confirm_password: yup
      .string()
      .oneOf([yup.ref("plainPassword"), null], "Does not match with password!")
      .required("Required"),
  })
  .required();

const Register = (props) => {
  const { t } = useTranslation();
  const {
    register,
    formState: { errors },
    handleSubmit,
  } = useForm({
    mode: "onChange",
    resolver: yupResolver(schema),
  });
  let navigate = useNavigate();

  const handleSubmition = (data) => props.registerUser({ ...data }, navigate);

  const onErrors = (errors) => console.log(errors);

  return (
    <Form
      className="container-sm form-registration mb-3 p-2"
      method="post"
      id="register-form"
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
        {t("register")}
      </h1>
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
      <div className="register-form-content d-flex flex-wrap gap-2">
        <div className="register-form-membre flex-fill">
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
            <label htmlFor="InputEmail" className="form-label">
              {t("email", { ns: "login" })}
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
          <div className="mb-3">
            <label htmlFor="InputPassword" className="form-label">
              {t("password", { ns: "login" })}
            </label>
            <input
              type="password"
              className="form-control"
              id="InputPassword"
              name="plainPassword"
              aria-invalid={errors?.plainPassword ? "true" : "false"}
              {...register("plainPassword")}
            />
            {errors?.plainPassword && (
              <small className="text-danger">
                {errors?.plainPassword.message}
              </small>
            )}
          </div>
        </div>
        <div className="register-form-user flex-fill">
          <div className="mb-3">
            <label htmlFor="InputUsername" className="form-label">
              {t("username", { ns: "login" })}
            </label>
            <input
              type="text"
              className="form-control"
              name="username"
              id="InputUsername"
              aria-invalid={errors?.username ? "true" : "false"}
              {...register("username")}
            />
            {errors?.username && (
              <small className="text-danger">{errors?.username.message}</small>
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
            <label htmlFor="InputEmailConfirm" className="form-label">
              {t("confirm_email", { ns: "login" })}
            </label>
            <input
              type="email"
              className="form-control"
              name="confirm_email"
              id="InputEmailConfirm"
              aria-invalid={errors?.confirm_email ? "true" : "false"}
              {...register("confirm_email")}
            />
            {errors?.confirm_email && (
              <small className="text-danger">
                {errors?.confirm_email.message}
              </small>
            )}
          </div>
          <div className="mb-3">
            <label htmlFor="InputPasswordConfirm" className="form-label">
              {t("confirm_password", { ns: "login" })}
            </label>
            <input
              type="password"
              className="form-control"
              id="InputPasswordConfirm"
              name="confirm_password"
              aria-invalid={errors?.confirm_password ? "true" : "false"}
              {...register("confirm_password")}
            />
            {errors?.confirm_password && (
              <small className="text-danger">
                {errors?.confirm_password.message}
              </small>
            )}
          </div>
        </div>
      </div>
      <button
        type="submit"
        className="btn btn-primary"
        disabled={props.isLoading}
      >
        {t("register")}
      </button>
    </Form>
  );
};

const mapStateToProps = (state) => ({
  error: state.errors,
  isLoading: state.loading,
});

const mapActionsToProps = {
  registerUser,
  clearErrors,
};

export default connect(mapStateToProps, mapActionsToProps)(Register);
