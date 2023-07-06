import React from "react";
import { Form } from "react-router-dom";
import { useTranslation } from "react-i18next";
import { useForm } from "react-hook-form";
import { connect } from "react-redux";
import { useNavigate, useLocation } from "react-router-dom";
import { loginUser, clearErrors } from "./../redux/actions/UserActions";

const Login = (props) => {
  const { t } = useTranslation();
  const {
    register,
    formState: { errors },
    handleSubmit,
  } = useForm({
    mode: "onChange",
  });
  const location = useLocation();
  const from = location.state?.from?.pathname || "/membre";
  let navigate = useNavigate();

  const handleLogin = (data) => props.loginUser(data, navigate, from);

  const registerOptions = {
    username: { required: "Email | username is required" },
    password: {
      required: "Password is required",
      minLength: {
        value: 4,
        message: "Password must have at least 4 characters",
      },
    },
  };

  return (
    <Form
      className="container-xs form-registration mb-3 p-2"
      method="post"
      id="login-form"
      onSubmit={handleSubmit(handleLogin)}
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
        {t("connection", { ns: "login" })}
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
      <div className="mb-3">
        <label htmlFor="InputEmail" className="form-label">
          {t("username", { ns: "login" })}
        </label>
        <input
          type="text"
          className="form-control"
          name="username"
          id="InputEmail"
          aria-invalid={errors?.username ? "true" : "false"}
          {...register("username", registerOptions.username)}
        />
        {errors?.username && (
          <small className="text-danger">{errors?.username.message}</small>
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
          aria-invalid={errors?.password ? "true" : "false"}
          {...register("password", registerOptions.password)}
        />
        {errors?.password && (
          <small className="text-danger">{errors?.password.message}</small>
        )}
      </div>
      <div className="mb-3 form-check">
        <input
          type="checkbox"
          name="checkMe"
          className="form-check-input"
          id="CheckMe"
          {...register("checkMe")}
        />
        <label className="form-check-label" htmlFor="CheckMe">
          {t("rememberme", { ns: "login" })}
        </label>
      </div>
      <button
        type="submit"
        className="btn btn-primary"
        disabled={props.isLoading}
      >
        {t("login")}
      </button>
    </Form>
  );
};

const mapStateToProps = (state) => ({
  error: state.errors,
  isLoading: state.loading,
});

const mapActionsToProps = {
  loginUser,
  clearErrors,
};

export default connect(mapStateToProps, mapActionsToProps)(Login);
