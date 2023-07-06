import React from "react";
import { useTranslation } from "react-i18next";
import { useForm } from "react-hook-form";
import { connect } from "react-redux";
import Swal from "sweetalert2";
import { clearErrors, loading } from "../redux/actions/UserActions";
import { yupResolver } from "@hookform/resolvers/yup";
import * as yup from "yup";
import CONSTANTS from "./../../utils/Constants";

const schema = yup
  .object({
    name: yup.string().required(),
    contacts: yup.string().required(),
    from: yup.string().email().required("Your email address is required"),
    subject: yup.string().required("the subject of your mail is required"),
    email_body: yup.string().required("The content of your mail is required"),
  })
  .required();

const Contact = (props) => {
  const { t } = useTranslation();
  const {
    register,
    formState: { errors },
    handleSubmit,
  } = useForm({
    mode: "onChange",
    resolver: yupResolver(schema),
  });

  const submitForm = async (data) => {
    props.loading();
    const res = await fetch(`${CONSTANTS.BASE_URL}/contactsending`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(data),
    });
    console.log(res);
    if (res.status === 200) {
      Swal.fire(t("contact.label"), t("contact.success"), "success");
    } else {
      Swal.fire(t("error"), t("contact.error"), "error");
    }
    props.clearErrors();
  };

  return (
    <div className="container mt-3">
      <div className="row">
        <div className="col-sm-6 col-md-6 align-self-center">
          <h1 className="text-center text-uppercase app-page-title">
            {t("contact.title")}
          </h1>
          {/* <!-- contact form --> */}
          <form
            onSubmit={handleSubmit(submitForm)}
            style={{ position: "relative" }}
          >
            {props.isLoading && (
              <div className="d-flex align-items-center justify-content-center w-100 overlay">
                <div className="spinner-border text-primary" role="status">
                  <span className="visually-hidden">Loading...</span>
                </div>
              </div>
            )}
            {/* <!-- name --> */}
            <div className="form-group mb-3">
              <label htmlFor="name">{t("contact.name")}</label>
              <input
                type="name"
                name="name"
                className="form-control"
                id="name"
                placeholder={t("contact.name_place_holder")}
                aria-invalid={errors?.name ? "true" : "false"}
                {...register("name")}
              />
              {errors?.name && (
                <small className="text-danger">{errors?.name.message}</small>
              )}
            </div>

            {/* <!-- contacts --> */}
            <div className="form-group mb-3">
              <label htmlFor="contacts">{t("contact.contacts")}</label>
              <input
                type="text"
                name="contacts"
                className="form-control"
                id="contacts"
                placeholder={t("contact.contacts_place_holder")}
                aria-invalid={errors?.contacts ? "true" : "false"}
                {...register("contacts")}
              />
              {errors?.contacts && (
                <small className="text-danger">
                  {errors?.contacts.message}
                </small>
              )}
            </div>

            {/* <!-- email --> */}
            <div className="form-group mb-3">
              <label htmlFor="from">{t("contact.from")}</label>
              <input
                type="email"
                name="from"
                className="form-control"
                id="from"
                placeholder={t("contact.from_place_holder")}
                aria-invalid={errors?.from ? "true" : "false"}
                {...register("from")}
              />
              {errors?.from && (
                <small className="text-danger">{errors?.from.message}</small>
              )}
            </div>

            {/* <!-- subject --> */}
            <div className="form-group mb-3">
              <label htmlFor="subject">{t("contact.subject")}</label>
              <input
                type="text"
                name="subject"
                className="form-control"
                id="subject"
                placeholder={t("contact.subject_place_holder")}
                aria-invalid={errors?.subject ? "true" : "false"}
                {...register("subject")}
              />
              {errors?.subject && (
                <small className="text-danger">{errors?.subject.message}</small>
              )}
            </div>

            <div className="form-group mb-3">
              <label htmlFor="email_body">{t("contact.message")}</label>
              <textarea
                className="form-control"
                id="email_body"
                rows="5"
                aria-invalid={errors?.email_body ? "true" : "false"}
                {...register("email_body")}
              ></textarea>
              {errors?.email_body && (
                <small className="text-danger">
                  {errors?.email_body.message}
                </small>
              )}
            </div>

            <button type="submit" className="btn btn-primary">
              {t("contact.submit")}
            </button>
          </form>
        </div>
        <div className="col-sm-6 col-md-6 d-flex flex-column justify-content-center">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63754.14956751917!2d11.112326317706147!3d2.9210530461124153!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x10884e2ebbfc3e21%3A0x29c627050a4c4f56!2sAncien%20Campus%20FASA%5CFMBEE%20antenne%20Ebolowa!5e0!3m2!1sfr!2scm!4v1686334454670!5m2!1sfr!2scm"
            width="100%"
            height="500"
            style={{ border: 0, marginBlockStart: 12 }}
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
      </div>
    </div>
  );
};

const mapStateToProps = (state) => ({
  error: state.errors,
  isLoading: state.loading,
});

const mapActionsToProps = {
  loading,
  clearErrors,
};

export default connect(mapStateToProps, mapActionsToProps)(Contact);
