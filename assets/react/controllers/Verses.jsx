import React from "react";
import Slider from "react-slick";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";

class VerseSlide extends React.Component {
  render() {
    const { content, source, ...props } = this.props;
    return (
      <div {...props}>
        <figure className="text-center">
          <blockquote className="blockquote">
            <p>{content}</p>
          </blockquote>
          <figcaption className="blockquote-footer">
            {source} <cite title="Source Title">The Bible</cite>
          </figcaption>
        </figure>
      </div>
    );
  }
}

const versets = [
  {
    content:
      "For God did not send his Son into the world to condemn the world, but to save the world through him.",
    source: "John 3:17",
  },
  {
    content:
      "Car Dieu n'a pas envoyé son Fils dans le monde pour condamner le monde, mais pour sauver le monde par lui.",
    source: "John 3:17",
  },
  {
    content:
      "For if, by the trespass of the one man, death reigned through that one man, how much more will those who receive God's abundant provision of grace!",
    source: "Romans 5:17 (NIV)",
  },
  {
    content:
      "Car si, par l'offense d'un seul homme, la mort a régné par cet homme, à plus forte raison ceux qui reçoivent l'abondante provision de grâce de Dieu !",
    source: "Romans 5:17 (NIV)",
  },
  {
    content:
      "For God so loved the world that he gave his one and only begotten Son, that who ever believes in him shall not perish but have eternal life",
    source: "John 3:16 (KJV)",
  },
  {
    content:
      "Car Dieu a tant aimé le monde qu'il a donné son Fils unique, afin que quiconque croit en lui ne périsse pas mais qu'il ait la vie éternelle.",
    source: "John 3:16 (KJV)",
  },
];

const Verses = () => {
  const settings = {
    dots: true,
    infinite: true,
    speed: 10000,
    slidesToShow: 1,
    slidesToScroll: 1,
    autoplay: true,
  };
  return (
    <section className="section-verses">
      <div className="section-title">
        <h2>Bible verses</h2>
      </div>
      <Slider {...settings}>
        {versets.map((verset, index) => (
          <VerseSlide
            key={`verset${index}`}
            content={verset.content}
            source={verset.source}
          />
        ))}
      </Slider>
    </section>
  );
};

export default Verses;
