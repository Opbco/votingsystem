import React, { useState } from "react";
import { useTranslation } from "react-i18next";
import { useForm } from "react-hook-form";
import { Form, useNavigate } from "react-router-dom";
import { yupResolver } from "@hookform/resolvers/yup";
import * as yup from "yup";
import useStepForm from "../../utils/useStepForm";
import ConcoursExamForm from "./../ConcoursExamForm";
import ConcoursDiplome from "./../ConcoursDiplome";
import ConcoursCandidat from "./../ConcoursCandidat";

const schema = yup
  .object({
    nbAttempt: yup.number().positive().integer().required("The  is required"),
    level: yup.string().required(),
    language: yup.string().required(),
    speciality: yup.string().required("The department is required"),
    examCenter: yup.string().required("Examination center is required"),
    firstName: yup.string(),
    lastName: yup.string().required(),
    dob: yup.date().min("01/01/1920").required(),
    pob: yup.string().required(),
    gender: yup.string().required("Your gender is required"),
    nationality: yup.string().required("Your nationality is required"),
    divisionOrigin: yup.string().required("Division of origin is required"),
    email: yup.string(),
    fatherName: yup.string().required(),
    motherName: yup.string().required(),
    phone: yup
      .string()
      .matches(/^[(]?[0-9]{3}[)]?[0-9]{7}[\d{1}]?[\d{1}]?$/, {
        message: "Invalid phone number",
      })
      .required(),
    parentsPhones: yup
      .string()
      .matches(/^[(]?[0-9]{3}[)]?[0-9]{7}[\d{1}]?[\d{1}]?$/, {
        message: "Invalid phone number",
      }),
    whatsapp: yup
      .string()
      .matches(/^[(]?[0-9]{3}[)]?[0-9]{7}[\d{1}]?[\d{1}]?$/, {
        message: "Invalid phone number",
      }),
    bp: yup.string(),
  })
  .required();

const INIT_CANDIDAT = {
  id: 1,
  level: "",
  language: "",
  speciality: undefined,
  nbAttempt: undefined,
  examCenter: "",
  status: "",
  firstName: "",
  lastName: "",
  gender: "",
  dob: undefined,
  pob: "",
  nationality: "",
  divisionOrigin: "",
  email: "",
  phone: "",
  fatherName: "",
  motherName: "",
  parentsPhones: "",
  bp: "",
  avatar: -1,
  whatsapp: "",
  diplomas: [],
};

const INIT_DIPLOMA = {
  id: -1,
  name: "",
  speciality: "",
  year: undefined,
  score: undefined,
  school: "",
};

const ConcoursForm = () => {
  const { t } = useTranslation();
  const [candidat, setCandidat] = useState(INIT_CANDIDAT);
  const navigate = useNavigate();
  const {
    register,
    control,
    formState: { errors },
    handleSubmit,
  } = useForm({
    defaultValues: candidat,
    mode: "onChange",
    resolver: yupResolver(schema),
  });
  const {
    currentStepIndex,
    steps,
    step,
    isFirstStep,
    isLastStep,
    next,
    prev,
    goTo,
  } = useStepForm([
    <ConcoursExamForm
      register={register}
      t={t}
      errors={errors}
      control={control}
    />,
    <ConcoursCandidat
      register={register}
      t={t}
      errors={errors}
      control={control}
    />,
    <ConcoursDiplome register={register} t={t} />,
  ]);

  const handleSubmition = (data) => {
    console.log(data);
    navigate(`/concours-form/${data.id}`);
  };

  const onErrors = (errors) => {
    console.log(errors);
    goTo(0);
  };

  return (
    <Form
      onSubmit={handleSubmit(handleSubmition, onErrors)}
      style={{ padding: "2rem" }}
    >
      <div className="d-flex justify-content-end align-items-center">{`${
        currentStepIndex + 1
      } / ${steps.length}`}</div>
      <div className="content-form">{step}</div>
      <div
        className="mt-3 d-flex justify-content-end g-2 align-items-center"
        style={{ gap: "1rem" }}
      >
        {!isFirstStep && (
          <button className="btn btn-secondary" type="button" onClick={prev}>
            {t("prev")}
          </button>
        )}
        {!isLastStep ? (
          <button className="btn btn-primary" type="button" onClick={next}>
            {t("next")}
          </button>
        ) : (
          <button className="btn btn-done" type="submit">
            {t("submit")}
          </button>
        )}
      </div>
    </Form>
  );
};

export default ConcoursForm;
