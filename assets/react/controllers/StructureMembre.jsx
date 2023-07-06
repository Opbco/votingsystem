import React, { useEffect, useState } from "react";
import { useTranslation } from "react-i18next";
import { connect } from "react-redux";
import { setErrors, clearErrors, loading } from "./redux/actions/UserActions";
import CONSTANTS from "../utils/Constants";
import { format } from "date-fns";
import useApiAxios from "./redux/requests/useApiAxios";
import publicAxios from "./redux/requests/publicAxios";
import { AiOutlineEdit, AiFillDelete } from "react-icons/ai";
import { BsHouseAdd } from "react-icons/bs";
import Modal from "./Modal";
import Swal from "sweetalert2";
import FormStructures from "./FormStructures";

function StructureMembre(props) {
  const membre = props.membre;
  const [myStructures, setMyStructure] = useState([]);
  const [current, setCurrent] = useState(null);
  const [isShown, setIsShown] = useState(false);
  const [rerender, setRerender] = useState(0);
  const { t } = useTranslation();
  const privateAxios = useApiAxios();
  const closeButton = React.createRef();
  const modal = React.createRef();

  useEffect(() => {
    if (membre?.id > 0) {
      (() => {
        publicAxios
          .get(
            `${CONSTANTS.BASE_API_URL}/open/personnels/${membre?.id}/structures?all=1`
          )
          .then((res) => {
            if (res.status == 200) {
              setMyStructure(res.data);
            }
          })
          .catch((error) => {
            props.setErrors(error.message);
          });
      })();
    }
  }, [membre, rerender]);

  const showModal = () => {
    setIsShown(() => {
      closeButton.current?.focus();
      return true;
    });
    toggleScrollLock();
  };

  const closeModal = () => {
    setIsShown(false);
    toggleScrollLock();
  };

  const onKeyDown = (event) => {
    if (event.keyCode === 27) {
      closeModal();
    }
  };

  const onClickOutside = (event) => {
    if (modal.current && modal.current.contains(event.target)) return;
    closeModal();
  };

  const toggleScrollLock = () => {
    document.querySelector("html").classList.toggle("scroll-lock");
  };

  const deleteStrucuture = (value) => {
    Swal.fire({
      title: t("delete.question"),
      icon: "question",
      showCancelButton: true,
      cancelButtonColor: "#02a1c5",
      confirmButtonText: t("delete.btn"),
      confirmButtonColor: "#a62b2b",
    }).then((result) => {
      if (result.isConfirmed) {
        privateAxios
          .delete(`/structurePositions/${value.id}`)
          .then((res) => {
            Swal.fire(
              t("delete.label"),
              t("delete.successStrucuture"),
              "success"
            );
            setRerender((v) => v + 1);
          })
          .catch(function (error) {
            Swal.fire(t("error"), error.message, "error");
          });
      }
    });
  };

  return (
    <div className="m-4 pt-2 pb-3">
      <h1 className="text-center text-uppercase app-page-title">
        {t("membre_professional")}
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
      <section className="container-fluid mt-3">
        <div className="d-flex justify-content-end mt-5 mb-1">
          <button
            onClick={() => {
              setCurrent({
                id: -1,
                structure: null,
                fonction: null,
                status: "encours",
                dateAffectation: format(new Date(), "dd-MM-yyyy"),
                edit: false,
              });
              showModal();
            }}
            className="btn btn-primary btn-flat"
          >
            <BsHouseAdd /> {t("structure.add")}
          </button>
        </div>
        <table className="table table-hover table-condensed table-responsive">
          <thead className="table-primary">
            <tr>
              <th scope="col">#</th>
              <th scope="col">{t("structure.date_assumption")}</th>
              <th scope="col">{t("structure.continent")}</th>
              <th scope="col">{t("structure.country")}</th>
              <th scope="col">{t("structure.state")}</th>
              <th scope="col">{t("structure.division")}</th>
              <th scope="col">{t("structure.subdivision")}</th>
              <th scope="col">{t("structure.name")}</th>
              <th scope="col">{t("structure.function")}</th>
              <th scope="col">{t("structure.status")}</th>
              <th scope="col">{t("structure.actions")}</th>
            </tr>
          </thead>
          <tbody>
            {myStructures.map((value, index) => (
              <tr key={`structure${index}`}>
                <th scope="row">{index + 1}</th>
                <td>
                  {format(new Date(value.dateAffectation), "dd MMM yyyy")}
                </td>
                <td>
                  {
                    value.structure.subDivision.department.state.country
                      .continent.name
                  }
                </td>
                <td>
                  {value.structure.subDivision.department.state.country.name}
                </td>
                <td>{value.structure.subDivision.department.state.name}</td>
                <td>{value.structure.subDivision.department.name}</td>
                <td>{value.structure.subDivision.name}</td>
                <td>{value.structure.name}</td>
                <td>{value.fonction.name}</td>
                <td>
                  <span
                    className={
                      value.status == "encours"
                        ? "badge text-bg-success"
                        : value.status == "retraite"
                        ? "badge text-bg-danger"
                        : "badge text-bg-warning"
                    }
                  >
                    {value.status}
                  </span>
                </td>
                <td>
                  <div
                    className="btn-group me-2"
                    role="group"
                    aria-label="Action group"
                  >
                    <button
                      onClick={() => {
                        setCurrent({ ...value, edit: true });
                        showModal();
                      }}
                      type="button"
                      className="btn"
                    >
                      <AiOutlineEdit color="#02a1c5" />
                    </button>
                    <button
                      type="button"
                      onClick={() => deleteStrucuture(value)}
                      className="btn"
                    >
                      <AiFillDelete color="#a62b2b" />
                    </button>
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
        {isShown && (
          <Modal
            modalRef={modal}
            buttonRef={closeButton}
            closeModal={closeModal}
            onKeyDown={onKeyDown}
            onClickOutside={onClickOutside}
          >
            <FormStructures
              structureP={current}
              setRerender={setRerender}
              closeModal={closeModal}
            />
          </Modal>
        )}
      </section>
    </div>
  );
}

const mapStateToProps = (state) => ({
  error: state.errors,
  isLoading: state.loading,
});

const mapActionsToProps = {
  setErrors,
  loading,
  clearErrors,
};

export default connect(mapStateToProps, mapActionsToProps)(StructureMembre);
