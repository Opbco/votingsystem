import React from "react";

const StepFormWrapper = ({ title, children }) => {
  return (
    <fieldset>
      <legend>{title}</legend>
      {children}
    </fieldset>
  );
};

export default StepFormWrapper;
