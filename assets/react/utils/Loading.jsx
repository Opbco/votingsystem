import React from "react";

const Loading = () => {
  return (
    <div>
      <div
        className="d-flex align-items-lg-center justify-content-center w-100 "
        style={{ minHeight: "100vh" }}
      >
        <div className="spinner-border text-primary" role="status">
          <span className="visually-hidden">Loading...</span>
        </div>
      </div>
    </div>
  );
};

export default Loading;
