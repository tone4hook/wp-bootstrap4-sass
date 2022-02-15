// We are using node's native package 'path'
// https://nodejs.org/api/path.html
const path = require("path");

const webpack = require("webpack"); // reference to webpack Object
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

// Constant with our paths
const paths = {
	DIST: path.resolve(__dirname, "dist"),
	SRC: path.resolve(__dirname, "src"),
};

// Webpack configuration
module.exports = {
	entry: [path.join(paths.SRC, "index.js")],
	output: {
		path: paths.DIST,
		filename: "main.bundle.js",
	},
	watch: true,
	// Adding jQuery as external library
	externals: {
		jquery: "jQuery",
	},
	// Tell webpack to use html plugin -> ADDED IN THIS STEP
	// index.html is used as a template in which it'll inject bundled app.
	plugins: [
		new webpack.ProvidePlugin({
			$: "jquery",
			jQuery: "jquery",
			Popper: "popper.js",
		}),
		new MiniCssExtractPlugin({
			filename: "style.min.css",
		}),
	],
	// Loaders configuration -> ADDED IN THIS STEP
	// We are telling webpack to use "babel-loader" for .js and .jsx files
	module: {
		rules: [
			{
				test: /\.m?js$/,
				exclude: /(node_modules|bower_components)/,
				use: {
					loader: "babel-loader",
					options: {
						presets: ["@babel/preset-env"],
					},
				},
			},
			{
				test: /\.(sa|sc|c)ss$/,
				use: [
					MiniCssExtractPlugin.loader,
					"css-loader",
					"postcss-loader",
					"sass-loader",
				],
			},
		],
	},
	// Enable importing JS files without specifying their's extenstion -> ADDED IN THIS STEP
	//
	// So we can write:
	// import MyComponent from './my-component';
	//
	// Instead of:
	// import MyComponent from './my-component.jsx';
	resolve: {
		extensions: [".js", ".jsx", ".scss"],
	},
};
