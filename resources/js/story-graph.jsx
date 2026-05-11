import React, { useCallback, useEffect, useState } from "react";
import { createRoot } from "react-dom/client";

import {
    ReactFlow,
    Background,
    Controls,
    MiniMap,
    applyNodeChanges,
    applyEdgeChanges,
} from "@xyflow/react";

import "@xyflow/react/dist/style.css";

const rootElement = document.getElementById("story-graph-root");

function StoryGraph() {
    const graphUrl = rootElement.dataset.graphUrl;
    const positionUrlTemplate = rootElement.dataset.positionUrlTemplate;

    const [nodes, setNodes] = useState([]);
    const [edges, setEdges] = useState([]);

    useEffect(() => {
        fetchGraph();
    }, []);

    async function fetchGraph() {
        try {
            const response = await fetch(graphUrl);
            const json = await response.json();

            setNodes(json.data.nodes);
            setEdges(json.data.edges);
        } catch (error) {
            console.error("Errore caricamento grafo:", error);
        }
    }

    const onNodesChange = useCallback((changes) => {
        setNodes((currentNodes) =>
            applyNodeChanges(changes, currentNodes)
        );
    }, []);

    const onEdgesChange = useCallback((changes) => {
        setEdges((currentEdges) =>
            applyEdgeChanges(changes, currentEdges)
        );
    }, []);

    async function onNodeDragStop(event, node) {
        const url = positionUrlTemplate.replace("__NODE_ID__", node.id);

        try {
            await fetch(url, {
                method: "PATCH",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute("content"),
                },
                body: JSON.stringify({
                    position_x: Math.round(node.position.x),
                    position_y: Math.round(node.position.y),
                }),
            });
        } catch (error) {
            console.error("Errore salvataggio posizione nodo:", error);
        }
    }

    return (
        <ReactFlow
            nodes={nodes}
            edges={edges}
            onNodesChange={onNodesChange}
            onEdgesChange={onEdgesChange}
            onNodeDragStop={onNodeDragStop}
            fitView
        >
            <Background />
            <Controls />
            <MiniMap />
        </ReactFlow>
    );
}

if (rootElement) {
    createRoot(rootElement).render(<StoryGraph />);
}