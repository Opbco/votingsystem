import html2canvas from "html2canvas";
import jsPDF from "jspdf";
import React, { useState, useRef, useEffect } from "react";
import CONSTANTS from "../../utils/Constants";
import { useLoaderData } from "react-router-dom";
import DocumentHeader from "../DocumentHeader";
import { useTranslation } from "react-i18next";
import DocFormElement from "../DocFormElement";
import StepFormWrapper from "../StepFormWrapper";
import { format } from "date-fns";

export async function loader({ params }) {
  let concoursdata = null;
  const response = await fetch(
    `${CONSTANTS.BASE_API_URL}/open/concourscandidats/${params.concourscandId}`
  );
  if (response.status == 200) {
    concoursdata = await response.json();
  }
  return { concoursdata };
}

const FormConcoursPdf = () => {
  const { concoursdata } = useLoaderData();
  const { t } = useTranslation();
  const docRef = useRef();

  useEffect(() => {
    const printDoc = async () => {
      html2canvas(docRef.current, {
        allowTaint: true,
        windowWidth: docRef.current.scrollWidth,
        windowHeight: docRef.current.scrollHeight,
      }).then((canvas) => {
        const doc = new jsPDF({
          orientation: "portrait",
          unit: "mm",
          format: [190, 210],
        });
        // Your code to handle the canvas image
        doc.addImage(canvas.toDataURL("image/png"), "PNG", 0, 0);
        doc.save("RECEPISSE_CARD.pdf");
      });
    };
    printDoc();
  }, []);

  return (
    <div ref={docRef} className="concours-pdf-container">
      <DocumentHeader />
      <h1 className="doc-heading1">
        Candidature au Concours d'entree en
        {t(`concours.${concoursdata.level}`)}
      </h1>
      <h2 className="doc-subheading1">
        Departement de {concoursdata.speciality.name}
      </h2>

      <StepFormWrapper title="Information sur le candidat">
        <div className="mb-3">
          <DocFormElement
            label="Genre"
            labelTrans="Gender"
            value={concoursdata.candidat.gender}
          />
        </div>
        <div className="row g-2 mb-3">
          <div className="col-xl">
            <DocFormElement
              label="Prenoms"
              labelTrans="First Name"
              value={concoursdata.candidat.firstName}
            />
          </div>
          <div className="col-xl">
            <DocFormElement
              label="Noms"
              labelTrans="Name"
              value={concoursdata.candidat.lastName}
            />
          </div>
        </div>
        <div className="row g-2 mb-3">
          <div className="col-xl">
            <DocFormElement
              label="Date de naissance"
              labelTrans="Date of birth"
              value={format(new Date(concoursdata.candidat.dob), "dd MMM yyyy")}
            />
          </div>
          <div className="col-xl">
            <DocFormElement
              label="Lieu de naissance"
              labelTrans="Place of birth"
              value={concoursdata.candidat.pob}
            />
          </div>
        </div>
      </StepFormWrapper>
    </div>
  );
};

export default FormConcoursPdf;
