import html2canvas from "html2canvas";
import jsPDF from "jspdf";
import React, { useState, useLayoutEffect, useRef, useEffect } from "react";
import CardRecto from "./CardRecto";
import CardVerso from "./CardVerso";
import CONSTANTS from "../utils/Constants";

const Card = ({ personne, id }) => {
  const personnel = JSON.parse(personne);
  const [myStructures, setMyStructures] = useState([]);
  const [loading, setLoading] = useState(true);
  const docRectoRef = useRef();
  const docVertoRef = useRef();
  const docRef = useRef();

  useLayoutEffect(() => {
    (async () => {
      const response = await fetch(
        `${CONSTANTS.BASE_API_URL}/open/personnels/${personnel?.id}/structures`
      );
      if (response.status == 200) {
        const responseJson = await response.json();
        setMyStructures(responseJson);
        setLoading(false);
      }
    })();
  }, [loading]);

  useEffect(() => {
    const printDoc = async () => {
      const doc = new jsPDF({
        orientation: "landscape",
        unit: "mm",
        format: [150, 90],
      });
      const canvasR = await html2canvas(docRectoRef.current, {
        allowTaint: true,
      });
      const canvasV = await html2canvas(docVertoRef.current, {
        allowTaint: true,
      });
      doc.addImage(canvasR.toDataURL("image/png"), "PNG", 0, 0);
      doc.addPage();
      doc.addImage(canvasV.toDataURL("image/png"), "PNG", 0, 0);
      doc.save("ID_EPC_CARD.pdf");
    };
    if (!loading) {
      printDoc();
    }
  }, [loading]);

  return (
    <div ref={docRef} className="card-container">
      <CardRecto
        ref={docRectoRef}
        personnel={personnel}
        fonctions={myStructures.map((structure) => structure.fonction.name)}
      />
      <CardVerso
        ref={docVertoRef}
        personnel={personnel}
        fonctions={myStructures}
      />
    </div>
  );
};

export default Card;
