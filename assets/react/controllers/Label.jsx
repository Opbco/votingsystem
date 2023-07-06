import React from "react";

const Label = ({ trans, children }) => {
  const width =
    trans.length > children.length ? `${trans.length}ch` : "fit-content";
  return (
    <label style={{ width: width }} className="card-label" data-trans={trans}>
      {children}
    </label>
  );
};

export default Label;
