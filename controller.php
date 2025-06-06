<?php

    class PostController {
        public function index() {
            // List all posts
            header('Content-Type: application/json');
            echo json_encode(['data' => [/* posts array */]]);
        }

        public function show($id) {
            // Show single post
            header('Content-Type: application/json');
            echo json_encode(['data' => ['id' => $id, 'title' => 'Sample Post']]);
        }

        public function store() {
            // Create new post
            $data = json_decode(file_get_contents('php://input'), true);
            // Validate and save data
            header('Content-Type: application/json');
            http_response_code(201);
            echo json_encode(['data' => $data]);
        }

        public function update($id) {
            // Update existing post
            $data = json_decode(file_get_contents('php://input'), true);
            // Validate and update data
            header('Content-Type: application/json');
            echo json_encode(['data' => array_merge(['id' => $id], $data)]);
        }

        public function destroy($id) {
            // Delete post
            http_response_code(204);
        }
    }