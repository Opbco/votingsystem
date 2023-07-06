import React from "react";
import logoUEb from "./../../../public/images/logo-univ-ebolowa.png";
import logoIsabee from "./../../../public/images/logo-isabee.png";

const DocumentHeader = () => {
  return (
    <section className="doc-header">
      <div className="doc-header-french-content">
        <h3>MINISTERE DE L'ENSEIGNEMENTS SUPERIEUR</h3>
        <h3>UNIVERSITE D'EBOLOWA</h3>
        <h3>
          Institut Supérieur d’Agriculture, du Bois, de l’Eau et de
          l’Environnement
        </h3>
      </div>
      <div className="doc-header-logos">
        <img src={logoUEb} alt="logo university of ebolowa" />
        <img src={logoIsabee} alt="Logo ISABEE" />
      </div>
      <div className="doc-header-english-content">
        <h3>MINISTRY OF HIGHER EDUCATION</h3>
        <h3>UNIVERSITY OF EBOLOWA</h3>
        <h3>
          Higher Institute of Agriculture, Wood, Water and the environment
        </h3>
      </div>
    </section>
  );
};

export default DocumentHeader;
