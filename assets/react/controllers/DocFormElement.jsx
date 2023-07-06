import React from "react";
import Label from "./Label";

const DocFormElement = ({ label, labelTrans, value }) => {
  return (
    <div className="doc-form-item">
      <div className="doc-form-item-label">
        <Label trans={labelTrans}>{label}</Label>
      </div>
      <div className="doc-form-item-value">{value}</div>
    </div>
  );
};

export default DocFormElement;
