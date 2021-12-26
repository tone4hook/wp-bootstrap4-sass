// We are using node's native package 'path'
// https://nodejs.org/api/path.html
const path = require("path");

const webpack = require("webpack"); // reference to webpack Object

// using the newer beta version for >= Webpack 4
// the current version is only good for <= Webpack 3
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const extractSass = new MiniCssExtractPlugin({
  filename: "style.min.css",
});

// Constant with our paths
const paths = {
  DIST: path.resolve(__dirname, "dist"),
  SRC: path.resolve(__dirname, "src"),
};

// Webpack configuration
const plugins = [
	extractSass,
  new webpack.ProvidePlugin({
    $: "jquery",
    jQuery: "jquery",
    Popper: "popper.js",
  }),
  ];

(module.exports = {
  entry: [path.join(paths.SRC, "index.js")],
  output: {
      path: paths.DIST,
      filename: "main.bundle.js",
    },
    // Adding jQuery as external library
    externals: {
      jquery: "jQuery",
    },

    resolve: {
      extensions: [".js", ".jsx", ".scss"],
    },
	plugins,
	module: {
    rules: [
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
			MiniCssExtractPlugin.loader,
			"css-loader",
			"postcss-loader",
			"sass-loader",
		  ],
      },

      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: ["babel-loader"],
      },
      {
        test: /\.(woff|woff2|ttf|eot|svg)$/,
        type: 'asset/resource',
    generator: {
        filename: './fonts/[name][ext]',
    },
      },
   
    ],
  },
});