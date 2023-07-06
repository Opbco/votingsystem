import React, { useEffect, useState } from "react";
import publicAxios from "./redux/requests/publicAxios";
import MembreCard from "./MembreCard";
import Slider from "react-slick";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import "aos/dist/aos.css";
import { useTranslation } from "react-i18next";
import { useGetFeaturedMembres } from "./redux/services/openApi";
import Loading from "../utils/Loading";

const FeaturedMembres = () => {
  const { t } = useTranslation();
  const { data, isFetching, error } = useGetFeaturedMembres();
  const settings = {
    dots: true,
    infinite: true,
    speed: 10000,
    slidesToShow: 3,
    slidesToScroll: 3,
    initialSlide: 0,
    autoplay: true,
    responsive: [
      {
        breakpoint: 920,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2,
          initialSlide: 2,
        },
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
        },
      },
    ],
  };

  if (isFetching) return <Loading />;

  if (error) return <Error />;

  return (
    <section className="featured-membres" data-aos="fade-up-left">
      <div className="section-title">
        <h2>{t("featured_pastors")}</h2>
      </div>
      <div className="main-content">
        <Slider {...settings}>
          {data.map((membre, index) => (
            <MembreCard key={`membreFeatured${index}`} membre={membre} />
          ))}
        </Slider>
      </div>
    </section>
  );
};

const Error = () => (
  <div className="container-fluid d-flex justify-center items-center">
    <h1 className="font-bold text-2xl text-white mt-2">
      Something went wrong. Please try again.
    </h1>
  </div>
);

export default FeaturedMembres;
