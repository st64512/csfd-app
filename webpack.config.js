const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const path = require("path");

module.exports = {
    entry: ["./src/index.js", "./src/styles.scss"],
    output: {
        filename: "main.js",
        path: path.resolve(__dirname, "www"),
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "styles.css"
        }),
    ],
    module: {
        rules: [
            {
                test: /\.scss$/,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader
                    },
                    'css-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.css$/i,
                use: [MiniCssExtractPlugin.loader, "css-loader"],
            },
        ]
    }
};
